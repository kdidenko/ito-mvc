<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class AuthenticationController extends BaseActionControllerImpl {
	
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	
	/**
	 * @var  string defining the username field name
	 */
	const USERNAME = 'username';
	
	/**
	 * @var string defining the password field name
	 */
	const PASSWORD = 'password';
	
	/**
	 * @var string defining the users table name
	 */
	const USERS = 'users';
	
	/**
	 * @var string defining the enabled field name
	 */
	const ENABLED = 'enabled';
	
	const USERS_ID = 'user_id';
	/** User login handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function login($actionParams, $requestParams) {
		# calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		# setting the query variables
		$fields = self::ID . ', ' . self::USERNAME . ', ' . self::PASSWORD . ', ' . self::ENABLED;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $requestParams [self::USERNAME] . "'";
		
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		
		# authenticating user login
		if (count($result) > 0 && $result [0] [self::ENABLED] == 1) {
			if (isset ( $requestParams [self::PASSWORD] ) & $result [0] [self::PASSWORD] == md5 ( $requestParams [self::PASSWORD] )) {
				$id = $result [0] [self::ID];
				$session = SessionService::startSession ();
				SessionService::setAttribute ( self::USERS_ID, $id );
			} else {
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		} else {
			$location = $this->onFailure ( $actionParams );
			$this->forwardActionRequest ( $location );
		}
		
		return $mvc;
	}

}

?>