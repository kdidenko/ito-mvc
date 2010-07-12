<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class UserContentController extends ContentController {
	const RTMP = 'rtmp';
	const RESP = 'resp';
	const CHLG = 'chlg';
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ($requestParams['switch']) ) {
			$role = self::getUserRole();
			if ($role == UsersService::ROLE_MR) {
				SessionService::setRole ( SessionService::ROLE_MR );
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		}
		
		#school rate
			isset($requestParams['valuate'])&&isset($requestParams[SchoolService::ID])&&$requestParams[SchoolService::ID]!=NULL ? 
				SchoolService::rateSchool($requestParams[SchoolService::ID], $requestParams['valuate']) : 
					NULL;
					
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
	public function handleSchools($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		#school rate
			isset($requestParams['valuate'])&&isset($requestParams[SchoolService::ID])&&$requestParams[SchoolService::ID]!=NULL ? 
				SchoolService::rateSchool($requestParams[SchoolService::ID], $requestParams['valuate']) : 
					NULL;
		#sorting
		$order = NULL;
		if( isset($requestParams[SchoolService::RATE]) ){
			$order = $requestParams[SchoolService::RATE] == SQLClient::ASC ? 
				SchoolService::RATE . ' ' . SQLClient::ASC : 
					SchoolService::RATE . ' ' . SQLClient::DESC;
		}
		if( isset($requestParams[SchoolService::LANGUAGE]) ){
			$order = $requestParams[SchoolService::LANGUAGE] == SQLClient::ASC ? 
				SchoolService::LANGUAGE . ' ' . SQLClient::ASC : 
					SchoolService::LANGUAGE . ' ' . SQLClient::DESC;
		}
		
		#get schools list
		$list = SchoolService::getSchoolsList (null, null, $order);
		$list = self::createTeaser($list);
		$mvc->addObject ( 'list', $list );
		return $mvc;
	}
	public function handleSchoolDetails($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (isset($requestParams[SchoolService::ID])){
			#school rate
			isset($requestParams['valuate'])&&isset($requestParams[SchoolService::ID])&&$requestParams[SchoolService::ID]!=NULL ? 
				SchoolService::rateSchool($requestParams[SchoolService::ID], $requestParams['valuate']) : 
					NULL;
		
			#get school
			$where = SchoolService::ID . " = '" . $requestParams [SchoolService::ID] . "'";
			$list = SchoolService::getSchoolsList ( $where );
			$mvc->addObject ( 'list', $list [0]);
			
			#for users and visitor
			$where = CourseService::SCHOOL_ID . " = '" . $requestParams [CourseService::ID] . "'";
			$courselist = CourseService::getCoursesList ( $where );
			$courselist = self::createTeaser($courselist);
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
		}
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
		
		if(isset($requestParams['delete'])){
			TrainingsService::deleteTrainig($requestParams['delete']);
			$location = $this->onSuccess ( $actionParams );
			$this->forwardActionRequest($location);
		}
		#get schools and courses list (assigned to user) for creating new training
		$usCourseList = CourseService::getAccessCourses();
		$mvc->addObject ( 'usCourseList', $usCourseList );
		
		#update training
		if ( isset($requestParams['updateCurrent']) ) {
			#creatin index for training
			$id = $requestParams ["edit_id"];
			$fields = array ();
			$fields [] .= TrainingsService::TRN_NAME;
			$vals = array ();
			$vals [] .= $requestParams [TrainingsService::TRN_NAME];
			TrainingsService::updateFields( $id, $fields, $vals );
		}
		if ( isset($requestParams['update']) ) {
			$t_index = $requestParams ["edit_id"];	
			foreach ($usCourseList as $key => $value) {
				if ( isset ($requestParams['course' . $value[CourseService::ID]]) ) {
					TrainingsService::addTraining($requestParams, $t_index, $value[CourseService::ID]);
				}
			}
		}
		
		#creating new training
		if ( isset($requestParams['submit']) ) {
			#creatin index for training
			$fields = "MAX(" . TrainingsService::TRN_ID . ")" . SQLClient::SQL_AS . 'max';
			$from = TrainingsService::TRAININGS_TABLE;
			$trainingList = DBClientHandler::getInstance ()->execSelect ( $fields, $from, null, null, '', '' );
			$t_index = $trainingList == NULL ? 1 : $trainingList[0]['max'] + 1;
			//$where = TrainingsService::USER_ID . '=' . $user_id;
			//$groupBy = TrainingsService::TRN_ID;
			//$trainingList = TrainingsService::getTrainingsList($where, $groupBy);
			//$t_index = $trainingList == NULL ? 1 : count($trainingList) + 1;

			foreach ($usCourseList as $key => $value) {
				if ( isset ($requestParams['course' . $value[CourseService::ID]]) ) {
					TrainingsService::addTraining($requestParams, $t_index, $value[CourseService::ID]);					
				}
			}
		}
		
		#get trainings list
		$where = TrainingsService::USER_ID . "= '" . $user_id . "'";
		$groupBy = TrainingsService::TRN_ID;
		$trainingList = TrainingsService::getTrainingsList($where, $groupBy);
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
		/*$challenge = ChallengesService::getChallenge($requestParams[ExerciseService::ID]);
		$mvc->addObject ( 'challenge', $challenge );*/
		return $mvc;
	}
	public function handleEditTraining($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if(isset($requestParams[TrainingsService::ID])){
			$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
			
			if (isset($requestParams['del'])){
				$where = TrainingsService::COURSE_ID . '=' . $requestParams['del'];
				TrainingsService::deleteTrainig($requestParams[TrainingsService::ID], $where);
			}
			
			#get schools and courses list (assigned to user) for creating new training
			$training = TrainingsService::getTraining($requestParams[TrainingsService::ID]);
			$mvc->addObject('training', $training);
			$where = NULL;
			if (count($training)>0){
				$where = CourseService::COURSE_TABLE . '.' . CourseService::ID . SQLClient::NOT . 
							SQLClient::IN . '(';
				foreach($training as $key =>$value){
				 $where .= $value[TrainingsService::COURSE_ID];
				 $where .= count($training)>$key+1 ? ', ' : NULL;
				}
				$where .= ')';
			}
			$usCourseList = CourseService::getAccessCourses($where);
			$mvc->addObject ( 'usCourseList', $usCourseList );
		}
		return $mvc;
		
	}
	public function handleMyResponses($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		#get responses
		$limit = !isset($requestParams[ExerciseService::ID]) ? '0, 1' : $requestParams[ExerciseService::ID] . ', 1';
		$resp = ResponsesService::getResponses($user_id, $limit);
		if ($resp != NULL){
			$resp = $resp[0];
			$resp[ResponsesService::CHLG_DESC] = self::createTeaserWord($resp[ResponsesService::CHLG_DESC]);
			$resp[ResponsesService::EX_DESC] = self::createTeaserWord($resp[ResponsesService::EX_DESC]);
			$mvc->addObject ( 'resp', $resp );
			$resp_index = $resp[ResponsesService::ID];
			$pageTo = isset($requestParams['page']) ? $requestParams['page']*5 : 5;
			$pageFrom = isset($requestParams['page']) ? ($requestParams['page']-1)*5 : 0;
			$limit = "$pageFrom, $pageTo";
			$comments = ValuateService::getValuateList($resp_index, NULL, $limit);
			$mvc->addObject ( 'comments', $comments );
			if(count($comments)>0){
				$votes = ValuateService::countVotes($resp_index);
				$mvc->addObject ( 'votes', $votes );
				$sum = ValuateService::countNumberVotes($votes);
				$points = ValuateService::countPoints($votes, $sum);
				$mvc->addObject ( 'sum', $sum );
				$mvc->addObject ( 'points', $points );
				$scroller = ceil($sum/5);
				$mvc->addObject ( 'scroller', $scroller );
			}
		}
		return $mvc;
		
	}
	public function handleValuateResponses($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		if(isset($requestParams['delete'])){
			ValuationsService::deleteValuation($requestParams['delete']);
			//TODO: remove hard code
			$location = "valuate-responses.html";
			$this->forwardActionRequest($location);
		}
		
		#get schools and courses list (assigned to user) for creating new training
		//$usCourseList = CourseService::getAccessCourses();
		$where = TrainingsService::USER_ID . '=' . $user_id;
		$groupBy = TrainingsService::COURSE_ID;
		$usCourseList = TrainingsService::getTrainingsList($where, $groupBy);
		$mvc->addObject ( 'usCourseList', $usCourseList );
		
		#update valuatin
		if ( isset($requestParams['updateCurrent']) ) {
			#creatin index for training
			$id = $requestParams ["edit_id"];
			$fields = array ();
			$fields [] .= ValuationsService::V_NAME;
			$vals = array ();
			$vals [] .= $requestParams [ValuationsService::V_NAME];
			ValuationsService::updateFields( $id, $fields, $vals );
		}
		if ( isset($requestParams['update']) ) {
			$v_index = $requestParams ["edit_id"];
			foreach ($usCourseList as $key => $value) {
				if ( isset ($requestParams['course' . $value[TrainingsService::COURSE_ID]]) ) {
					ValuationsService::addValuations($requestParams, $v_index, $value[TrainingsService::COURSE_ID]);
				}
			}
		}
		#valuate responses
		if ( isset($requestParams['submit']) ) {
			if($requestParams[ValuateService::COMMENT]!=NULL && $requestParams[ValuateService::VALUATE]!=NULL){
				$resp_id = $requestParams[ValuateService::RESP_ID];
				$comment = $requestParams[ValuateService::COMMENT];
				$valuate = $requestParams[ValuateService::VALUATE];
				ValuateService::valuateResp($resp_id, $comment, $valuate);
			}
		}
		#creating new valuation
		if ( isset($requestParams['new']) ) {
			#creatin index for training
			$fields = "MAX(" . ValuationsService::V_ID . ")" . SQLClient::SQL_AS . 'max';
			$from = ValuationsService::V_TABLE;
			$valuationsList = DBClientHandler::getInstance ()->execSelect ( $fields, $from, null, null, '', '' );
			$v_index = $valuationsList == NULL ? 1 : $valuationsList[0]['max'] + 1;
			foreach ($usCourseList as $key => $value) {
				if ( isset ($requestParams['course' . $value[TrainingsService::COURSE_ID]]) ) {
					ValuationsService::addValuations($requestParams, $v_index, $value[TrainingsService::COURSE_ID]);
				}
			}
		}		
		
		#get valuation list
		$where = ValuationsService::USER_ID . "= '" . $user_id . "'";
		$groupBy = ValuationsService::V_ID;
		$valuationsList = ValuationsService::getValuationsList($where, $groupBy);
		$mvc->addObject ( 'valuationsList', $valuationsList );
			
		#get exercises for valuation
		if(isset($requestParams[ValuationsService::ID])){
			#creating "where" for sql query
			$training = ValuationsService::getValuation($requestParams[ValuationsService::ID]);
			$where = NULL;
			foreach ($training as $key => $value){
				$where .= ExerciseService::COURSE_ID . " ='". $value[ValuationsService::COURSE_ID] ."'";
				$where .= $key != count ($training) - 1 ? " OR " . ExerciseService::EXERCISES_TABLE . "." : null;			
			}
			$limit = $requestParams['ex'] <= 0 ? '0, 1' : $requestParams['ex']-1 . ', 1';
			$v_index = $requestParams[ValuationsService::ID];
			$resp = ResponsesService::getResponses(NULL, $limit, $v_index);
			if (count($resp)>0){
				foreach($resp as $key => $value){
					$resp[$key][ResponsesService::CHLG_DESC] = self::createTeaserWord($value[ResponsesService::CHLG_DESC]);
					$resp[$key][ResponsesService::EX_DESC] = self::createTeaserWord($value[ResponsesService::EX_DESC]);
				}
				$valuate = ValuateService::getValuateList($resp[0][ResponsesService::ID], $user_id);
				$mvc->addObject ( 'valuate', $valuate );
			}
			$mvc->addObject ( 'resp', $resp );
			
			#count responses votes 
			$resp_index = $resp[0][ResponsesService::ID];
			$votes = ValuateService::countVotes($resp_index);
			if($votes!=NULL){
				$mvc->addObject ( 'votes', $votes );
				$sum = ValuateService::countNumberVotes($votes);
				$points = ValuateService::countPoints($votes, $sum);
				$mvc->addObject ( 'sum', $sum );
				$mvc->addObject ( 'points', $points );
			}
		}
		return $mvc;
	}
	public function handleEditValuation($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if(isset($requestParams[ValuationsService::ID])){
			$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
			
			if (isset($requestParams['del'])){
				$where = ValuationsService::COURSE_ID . '=' . $requestParams['del'];
				ValuationsService::deleteValuation($requestParams[ValuationsService::ID], $where);
			}
			
			$valuation = ValuationsService::getValuation($requestParams[ValuationsService::ID]);
			$mvc->addObject('valuation', $valuation);
			#get schools and courses list (assigned to user) for creating new training
			$where = NULL;
			if (count($valuation)>0){
				$where = TrainingsService::TRAININGS_TABLE . '.' . TrainingsService::COURSE_ID . SQLClient::NOT . 
							SQLClient::IN . '(';
				foreach($valuation as $key =>$value){
				 $where .= $value[TrainingsService::COURSE_ID];
				 $where .= count($valuation)>$key+1 ? ', ' : NULL;
				}
				$where .= ') AND ';
			}
			$where .= TrainingsService::USER_ID . '=' . $user_id;
			$groupBy = TrainingsService::COURSE_ID;
			$usCourseList = TrainingsService::getTrainingsList($where, $groupBy);
			$mvc->addObject ( 'usCourseList', $usCourseList );
		}
		return $mvc;
	}
	public function handleStartChallenge($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset($requestParams[ExerciseService::ID])){
			$mvc->addObject(self::RTMP, '192.168.0.104');
			$username = SessionService::getAttribute(SessionService::USERNAME);
			$challenge = ChallengesService::getChallenge($requestParams[ExerciseService::ID]);
			#random select challenges
			$random = rand(0,count($challenge)-1) ;
			$chlg_index = $challenge[$random][ChallengesService::ID];
			$time = time();
			$resp = $username . '-' . $chlg_index . '-' . $time;
			$mvc->addObject(self::RESP, $resp);
			$chlg_name = $challenge[0][ChallengesService::NAME];
			$mvc->addObject ( self::CHLG, $chlg_name );
			$chlg_index = $challenge[0][ChallengesService::ID];
			$mvc->addObject ( ResponsesService::CHLG_INDEX, $chlg_index );
		}
		
		#for a while
		if (isset($requestParams['submit'])){
			ResponsesService::newResponse($requestParams);
		}
		
		return $mvc;
	}
	public function handleMyChallenges($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		if ( isset($requestParams['submit']) ){
			//server-side validation
			$error = array ();
			$error [] .= isset($requestParams [ChallengesService::CAPTION])&&$requestParams [ChallengesService::CAPTION] ? 
							false : 
								'Please enter caption';
			$error [] .= isset($requestParams [ChallengesService::DESCRIPTION])&&$requestParams [ChallengesService::DESCRIPTION] ? 
							false : 
								'Please enter description';
			$error = array_filter ( $error );
			if (isset ( $error ) && count ( $error ) == 0) {
				#submit new challenge to db
				$username = SessionService::getAttribute(SessionService::USERNAME);
				$time = time();
				$name = $username . '-' . $requestParams[ChallengesService::EX_INDEX] . '-' . $time;
				ChallengesService::newChallenge($requestParams, $name);
			} else {
				$mvc->addObject ( UsersService::ERROR, $error );
			}
		}
		
		#checking schools assigned
		$result = AssignmentsService::getSchool($user_id);
		if ($result != NULL){
			#get schools and courses list (assigned to user) for creating new challenge
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
			
			#get all exercises witch user can view
			$exList = ExerciseService::getAccessEx($user_id);
			$mvc->addObject ( 'exList', $exList);
		}
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