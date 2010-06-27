<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class NavigationController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type $actionParams
	 * @param unknown_type $requestParams
	 * @return $modelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$role = self::getUserRole();
		$mvc->addObject ( 'role', $role);
		
		//TODO: put navigation handling here
		return $mvc;		
	}
	public static function getUserRole(){
		#prepeare value for sql query
		$result = NULL; 
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		if($id != NULL){
			$fields = UsersService::ROLE;
			$from = UsersService::USERS;
			$where = UsersService::ID . "= '" . $id . "'";
			#get user role 
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result[0][UsersService::ROLE] : null;		
		}
		return $result;
	}
}

?>