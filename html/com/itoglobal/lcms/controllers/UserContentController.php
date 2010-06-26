<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class UserContentController extends ContentController {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ($requestParams['switch']) ) {
			$role = self::getUserRole();
			if ($role == UsersService::ROLE_MR) {
				SessionService::setRole ( SessionService::ROLE_MR );
				header("Location: /index.html");
				exit;
			}
		}
		
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		#checking schools assigned
		$result = AssignmentsService::getSchool($user_id);
		
		if ($result != NULL){
			$where = '';
			foreach($result as $key => $value){
				$where .= SchoolService::ID . " = '" . $value[AssignmentsService::SCHOOL_ID] . "'";
				$where .= $key != count ($result) - 1 ? " OR " . SchoolService::SCHOOLS_TABLE . "." : null;
			}
			$usSchList = SchoolService::getSchoolsList ($where);
			$usSchList = self::createTeaser($usSchList);
			$mvc->addObject ( 'usSchList', $usSchList );
		}
		
		return $mvc;
	}
	
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );

		#for all
		$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
		$list = SchoolService::getSchoolsList ( $where );
		$mvc->addObject ( 'list', $list [0]);
		
		#for users and visitor
		$where = CourseService::SCHOOL_ID . " = '" . $requestParams [CourseService::ID] . "'";
		$courselist = CourseService::getCoursesList ( $where );
		$mvc->addObject ( 'courselist', $courselist );
		
		#sign in / out to school
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$school_id = $requestParams[AssignmentsService::ID];
		isset($requestParams[AssignmentsService::SIGNOUT]) ?		
			$school_id = $requestParams[AssignmentsService::SIGNOUT] :
				null;
		
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$where = AssignmentsService::SCHOOL_ID . " = '" . $school_id . "' AND " . AssignmentsService::USER_ID . " = '" . $user_id . "'";
		#checking schools assigned
		$result = AssignmentsService::getSchool($user_id, $where);
		$mvc->addObject ( 'assign', $result );
		
		return $mvc;
	}
	
	public function handleSchoolAssigned($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		if (isset($requestParams[AssignmentsService::SIGNUP])){
			$school_id = $requestParams[AssignmentsService::SIGNUP];
			AssignmentsService::SignUpSchool($school_id, $user_id);
			$message = 'You succesfully signed up to this school'; 
		}
		
		if (isset($requestParams[AssignmentsService::SIGNOUT])){ 
			$school_id = $requestParams[AssignmentsService::SIGNOUT];
			AssignmentsService::SignOutSchool($school_id, $user_id);
			$message = 'You succesfully signed out from this school'; 
		}
		
		$mvc->addObject ( 'message', $message );
		
		return $mvc;
	}
	
	
	public function handleMyTrainings($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		#checking schools assigned
		$result = AssignmentsService::getSchool($user_id);
		if ($result != NULL){
			#get schools and courses list (assigned to user) for creating new training
			$where = '';
			$where_course = '';
			foreach($result as $key => $value){
				$where .= SchoolService::ID . " = '" . $value[AssignmentsService::SCHOOL_ID] . "'";
				$where .= $key != count ($result) - 1 ? " OR " . SchoolService::SCHOOLS_TABLE . "." : null;
				$where_course .= CourseService::SCHOOL_ID . " = '" . $value[AssignmentsService::SCHOOL_ID] . "'";
				$where_course .= $key != count ($result) - 1 ? " OR " . CourseService::COURSE_TABLE . "." : null;
			}
			$usSchList = SchoolService::getSchoolsList ($where);
			$usSchList = self::createTeaser($usSchList);
			$mvc->addObject ( 'usSchList', $usSchList );
		
			$usCourseList = CourseService::getCoursesList ($where_course);
			$mvc->addObject ( 'usCourseList', $usCourseList );
			
			#creating new training
			if ( isset($requestParams['submit']) ) {
				#creatin index for training
				$where = TrainingsService::USER_ID . '=' . $user_id;
				$groupBy = TrainingsService::TRN_ID;
				$trainingList = TrainingsService::getTrainingList($where, $groupBy);
				$t_index = $trainingList == NULL ? 1 : count($trainingList) + 1; 
	
				foreach ($usCourseList as $key => $value) {
					if ( isset ($requestParams['course' . $value[CourseService::ID]]) ) {
						# Insert new school to DB
						$fields = TrainingsService::TRN_ID . ", " . TrainingsService::TRN_NAME . ", " . TrainingsService::USER_ID . ", " . TrainingsService::COURSE_ID;
						$values = "'" . $t_index . "', '" . $requestParams [TrainingsService::TRN_NAME] . "', '" . $user_id . "' , '" . $value[CourseService::ID] . "'";
						$into = TrainingsService::TRAININGS_TABLE;
						DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );					
					}
				}
			}		
			
			#get trainings list
			$where = TrainingsService::USER_ID . "= '" . $user_id . "'";
			$groupBy = "'" . TrainingsService::TRN_ID . "'";
			$trainingList = TrainingsService::getTrainingList($where, $groupBy);
			$mvc->addObject ( 'trainingList', $trainingList );
				
			#get exercises for training
			if(isset($requestParams[TrainingsService::ID])){
				#creating "where" for sql query
				$training = TrainingsService::getTraining($requestParams[TrainingsService::ID]);
				$where = NULL;
				foreach ($training as $key => $value){
					$where .= ExerciseService::COURSE_ID . " ='". $value[TrainingsService::COURSE_ID] ."'";
					$where .= $key != count ($training) - 1 ? " OR " . ExerciseService::EXERCISES_TABLE . "." : null;			
				}
				$limit = $requestParams['ex'] <= 0 ? '0, 1' : $requestParams['ex']-1 . ', 1';
				$exerciselist = ExerciseService::getExercisesList($where, $limit);
				$exerciselist = self::createTeaser($exerciselist);
				$mvc->addObject ( 'exerciselist', $exerciselist);
			}
		} else {
			#if no assigne school
			$mvc->addObject ( 'noSchAssigne', TRUE ); 
		}
		
		return $mvc;
	}
	
	public function handleStartChallenge($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		return $mvc;
	}
	
	private static function getUserRole(){
		#prepeare value for sql query 
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = UsersService::ROLE;
		$from = UsersService::USERS;		
		$where = UsersService::ID . "= '" . $id . "'";
		#get user role 
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0][UsersService::ROLE] : null;		
		return $result;
	}
}

?>