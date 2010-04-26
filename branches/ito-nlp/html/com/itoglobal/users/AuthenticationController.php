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
	
	/** User login handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function login($actionParams, $requestParams) {
		$location = $this->onSuccess ( $actionParams );
		# setting the query variables
		$fields = self::ID . ', ' . self::USERNAME . ', ' . SessionService::FIRSTNAME . ', ' . SessionService::LASTNAME . ', ' . SessionService::EMAIL . ', ' . self::PASSWORD . ', ' . self::ENABLED . ', ' . SessionService::ROLE;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $requestParams [self::USERNAME] . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		# authenticating user login
		if (count ( $result ) > 0 && $result [0] [self::ENABLED] == 1) {
			if (isset ( $requestParams [self::PASSWORD] ) & $result [0] [self::PASSWORD] == md5 ( $requestParams [self::PASSWORD] )) {
				$id = $result [0] [self::ID];
				$session = SessionService::startSession ();
				SessionService::setAttribute ( SessionService::USERS_ID, $id );
				SessionService::setAttribute ( SessionService::USERNAME, $result [0] [SessionService::USERNAME] );
				SessionService::setAttribute ( SessionService::FIRSTNAME, $result [0] [SessionService::FIRSTNAME] );
				SessionService::setAttribute ( SessionService::LASTNAME, $result [0] [SessionService::LASTNAME]);
				SessionService::setAttribute ( SessionService::EMAIL, $result [0] [SessionService::EMAIL]);
				SessionService::setRole($result [0] [SessionService::ROLE]);
			} else {
				$location = $this->onFailure ( $actionParams );
			}
		} else {
			$location = $this->onFailure ( $actionParams );
		}
		$this->forwardActionRequest ( $location );
	}
	
	public function logout($actionParams, $requestParams) {
		SessionService::endSession();
		$location = $this->onSuccess ( $actionParams );
		$this->forwardActionRequest ( $location );
	}
}

?>