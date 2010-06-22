<?php

require_once 'com/itoglobal/lcms/controllers/ContentController.php';

class UserContentController extends ContentController {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (isset ($requestParams['switch']) ) {
			$role = self::getRole();
			if ($role == UsersService::ROLE_MR) {
				SessionService::setRole ( SessionService::ROLE_MR );
				header("Location: /index.html");
				exit;
			}
		}
		
		#for all
		$firstname = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( SessionService::FIRSTNAME, $firstname ) : null;
		$lastname = SessionService::getAttribute ( SessionService::LASTNAME );
		isset ( $lastname ) ? $mvc->addObject ( SessionService::LASTNAME, $lastname ) : null;
		
		#for user
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		$fields = AssignedService::SCHOOL_ID;
		$from = AssignedService::SCHOOLS_ASSIGNED;
		$where = AssignedService::USER_ID . "='" . $user_id . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		
		
		if (isset($result [0] [AssignedService::SCHOOL_ID])){
			$where = '';
			foreach($result as $key => $value){
				$where .= SchoolService::ID . " = '" . $value[AssignedService::SCHOOL_ID] . "'";
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
		
		#for user
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$school_id = $requestParams[AssignedService::ID];
		isset($requestParams[AssignedService::SIGNOUT]) ?		
			$school_id = $requestParams[AssignedService::SIGNOUT] :
				null;
		$fields = AssignedService::SCHOOL_ID;
		$from = AssignedService::SCHOOLS_ASSIGNED;
		$where = AssignedService::SCHOOL_ID . " = '" . $school_id . "' AND " . AssignedService::USER_ID . " = '" . $user_id . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		isset ($result) ? $mvc->addObject ( 'assign', $result ) : null;

		
		return $mvc;
	}
	
	public function handleSchoolAssigned($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		
		if (isset($requestParams[AssignedService::SIGNUP])){
			$school_id = $requestParams[AssignedService::SIGNUP];
			AssignedService::SignUpSchool($school_id, $user_id);
			$message = 'You succesfully signed up to this school'; 
		}
		
		if (isset($requestParams[AssignedService::SIGNOUT])){ 
			$school_id = $requestParams[AssignedService::SIGNOUT];
			AssignedService::SignOutSchool($school_id, $user_id);
			$message = 'You succesfully signed out from this school'; 
		}
		
		$mvc->addObject ( 'message', $message );
		
		return $mvc;
	}
	public function handleMyTrainings($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	private static function getRole(){
		#prepeare value for sql query 
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = UsersService::ROLE;
		$from = UsersService::USERS;		
		$where = UsersService::ID . "= '" . $id . "'";
		#get user role 
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;		
		return $result [UsersService::ROLE] ;
	}
}

?>