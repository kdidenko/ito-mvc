<?php

require_once 'com/ito-global/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	
	const USERNAME = 'username';
	
	const PASSWORD = 'password';
	
	const CONFIRM = 'confirm_password';
	
	const CRDATE = 'crdate';
	
	const USERS = 'users';
	
	public function registration($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
	

		$error = $_POST[self::USERNAME] == '' ? 'Please enter username <br />' : '' ;
		$error = self::CONFIRM == self::PASSWORD ? 'Please enter correct confirmation password' : '' ;
		if ($error != NULL){
			$location = $this->onFailure ( $actionParams );
			$this->forwardActionRequest ( $location );
		}
		
		
		$fields = self::USERNAME . ', ' . self::PASSWORD . ', ' . self::CRDATE;
	    $values = "'" . $_POST[self::USERNAME] . "','" . $_POST[self::PASSWORD] . "','" . gmdate("Y-m-d H:i:s") . "'" ;
	    $into = self::USERS;
	
	    $link = SQLClient::connect('ito_global', 'localhost', 'root', '');
	    $result = SQLClient::execInsert($fields, $values, $into, $link);
	    
		return $mvc;
	}
	
}


?>