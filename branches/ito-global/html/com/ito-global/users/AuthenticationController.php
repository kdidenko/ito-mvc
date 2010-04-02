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
		$where = self::USERNAME . " = '" . $_POST [self::USERNAME] . "'";

		$link = SQLClient::connect ( 'ito_global', 'localhost', 'root', '' );
		$result = SQLClient::execSelect ( $fields, $from, $where, '', '', '', $link );
		
		if (isset ( $result [0] [self::PASSWORD] )) {
			$id = $result [0] [self::ID];
			if ($result [0] [self::PASSWORD] == $_POST [self::PASSWORD]) {
				$session = SessionService::getSessionId ();
				SessionService::setAttribute ( self::USERS, $id );
			} else {
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		} else {
			$error = "please try again";
		}
		
		return $mvc;
	}

}

?>