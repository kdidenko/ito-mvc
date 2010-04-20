<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class AuthenticationController extends BaseActionControllerImpl {
	
	const ID = 'id';
	
	const USERNAME = 'username';
	
	const PASSWORD = 'password';
	
	const USERS = 'users';
	
	
	public function login($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		


		$fields = self::ID . ', ' . self::USERNAME . ', ' . self::PASSWORD;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $requestParams [self::USERNAME] . "'";

		$link = SQLClient::connect ( 'youcademy', 'localhost', 'root', '' );
		$result = SQLClient::execSelect ( $fields, $from, $where, '', '', '', $link );
		
		if (isset ( $result [0] [self::PASSWORD] )) {
			$id = $result [0] [self::ID];
			if ($result [0] [self::PASSWORD] == $requestParams [self::PASSWORD]) {
				$session = SessionService::getSessionId ();
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