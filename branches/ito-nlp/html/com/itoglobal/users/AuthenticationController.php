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
	
	/** User login handling controller method
	 * @param the $actionParams
	 * @param the $requestParams
	 * @return Object of ModelAndView
	 */
	public function login($actionParams, $requestParams) {
		# calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		# setting the query variables
		$fields = self::ID . ', ' . self::USERNAME . ', ' . self::PASSWORD;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $requestParams [self::USERNAME] . "'";
		# executing the query
		//TODO: use DAOServise instead of SQLClient  
		$link = SQLClient::connect ( 'youcademy', 'localhost', 'root', '' );
		$result = SQLClient::execSelect ( $fields, $from, $where, '', '', '', $link );
		# authenticating user login
		if (isset ( $result [0] [self::PASSWORD] )) {
			$id = $result [0] [self::ID];
			if ($result [0] [self::PASSWORD] == $requestParams [self::PASSWORD]) {
				$session = SessionService::startSession ();
				SessionService::setAttribute ( self::USERS, $id );
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