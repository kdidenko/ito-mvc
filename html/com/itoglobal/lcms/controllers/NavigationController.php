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
		
		$role = self::getRole();
		$mvc->addObject ( 'role', $role);
		
		//TODO: put navigation handling here
		return $mvc;		
	}
	public static function getRole(){
		#prepeare value for sql query 
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = UsersService::ROLE;
		$from = UsersService::USERS;		
		$where = UsersService::ID . "= '" . $id . "'";
		#get user role 
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;		
		return $result[UsersService::ROLE];
	}
}

?>