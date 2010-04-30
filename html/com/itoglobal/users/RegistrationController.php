<?php
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	
	public function registration($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (! isset ( $requestParams [UsersService::ID] )) {
			if (isset ( $requestParams ['submit'] )) {
				//server-side validation
				$error = UsersService::validation ( $requestParams );
				if (count ( $error ) == 0) {
					// Insert new users to DB
					$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ROLE;
					$hash = md5 ( rand ( 1, 9999 ) );
					$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','" . md5 ( $requestParams [UsersService::PASSWORD] ) . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','UR'";
					$into = UsersService::USERS;
					$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
					
					$fields = UsersService::ID;
					$from = UsersService::USERS;
					$where = UsersService::USERNAME . " = '" . $requestParams [UsersService::USERNAME] . "'";
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					$plain = $mvc->getProperty ( 'template' );
					$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/confirm-registration.html?id=' . $result [0] [UsersService::ID] . '&validation_id=' . $hash;
					MailersService::replaceVars ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $plain, $url );
					
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					$mvc->addObject ( UsersService::ERROR, $error );
				}
			}
		} else {
			self::confirmRegistration ( $requestParams [UsersService::ID], $requestParams [UsersService::VALIDATION] );
			$message = 'You completed registration.';
			$mvc->addObject ( 'message', $message );
		}
		return $mvc;
	
	}
	
	public function resetPassword($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (! isset ( $requestParams [UsersService::PASSWORD] )) {
			if (! isset ( $requestParams [UsersService::VALIDATION] )) {
				if (isset ( $requestParams [UsersService::EMAIL] )) {
					$fields = UsersService::FIRSTNAME . ',' . UsersService::LASTNAME . ',' . UsersService::USERNAME . ',' . UsersService::EMAIL;
					$from = UsersService::USERS;
					$where = UsersService::EMAIL . " = '" . $requestParams [UsersService::EMAIL] . "'";
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					if (isset ( $result [0] [UsersService::EMAIL]) && $requestParams [UsersService::EMAIL] != NULL ) {
						
						$fields = UsersService::VALIDATION;
						$hash = md5 ( rand ( 1, 9999 ) );
						$vals = "'" . $hash . "'";
						DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
						
						if (isset ( $result [0] [UsersService::FIRSTNAME] )) {
							$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
							MailersService::replaceVars ( $requestParams [UsersService::EMAIL], $result [0] [UsersService::USERNAME], $result [0] [UsersService::FIRSTNAME], $result [0] [UsersService::LASTNAME], $actionParams->property ['value'], $url );
							
							$location = $this->onSuccess ( $actionParams );
							$this->forwardActionRequest ( $location );
						}
					} else {
						$mvc->addObject ( UsersService::ERROR, 'Email address not found' );
					}}
			} else {
				$mvc->addObject ( UsersService::EMAIL, $requestParams [UsersService::EMAIL] );
				$mvc->addObject ( UsersService::VALIDATION, $requestParams [UsersService::VALIDATION] );
			}
		} else {
			$mvc->addObject ( UsersService::EMAIL, $requestParams [UsersService::EMAIL] );
			$mvc->addObject ( UsersService::VALIDATION, $requestParams [UsersService::VALIDATION] );
			# setting the query variables
			$fields = UsersService::VALIDATION;
			$from = UsersService::USERS;
			$where = UsersService::EMAIL . " = '" . $requestParams [UsersService::EMAIL] . "'";
			# executing the query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			if (isset ( $result [0] [UsersService::VALIDATION] ) && $result [0] [UsersService::VALIDATION] == $requestParams [UsersService::VALIDATION]) {
				$error = array ();
				$error [] .= UsersService::checkPassword ( $requestParams [UsersService::PASSWORD] );
				$error [] .= UsersService::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] );
				$error = array_filter ( $error );
				if (count ( $error ) == 0) {
					self::createNewPassword ( $requestParams [UsersService::EMAIL], $requestParams [UsersService::PASSWORD] );
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					$mvc->addObject ( UsersService::ERROR, $error );
				}
			} else {
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		}
		return $mvc;
	}
	
	private function createNewPassword($email, $password) {
		$fields = UsersService::PASSWORD;
		$from = UsersService::USERS;
		$vals = "'" . md5 ( $password ) . "'";
		$where = UsersService::EMAIL . " = '" . $email . "'";
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public function confirmRegistration($id, $hash) {
		# setting the query variables
		$fields = UsersService::VALIDATION . ',' . UsersService::USERNAME;
		$from = UsersService::USERS;
		$where = UsersService::ID . " = '" . $id . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		
		if (isset ( $result [0] [UsersService::VALIDATION] ) && $result [0] [UsersService::VALIDATION] == $hash) {
			StorageService::createDirectory ( 'storage/uploads/' . $result [0] [UsersService::USERNAME] );
			StorageService::createDirectory ( 'storage/uploads/' . $result [0] [UsersService::USERNAME] . '/profile' );
			StorageService::createDirectory ( 'storage/uploads/' . $result [0] [UsersService::USERNAME] . '/trainings' );
			$path = 'storage/uploads/' . $result [0] [UsersService::USERNAME] . '/profile/avatar.jpg';
			copy ( 'storage/uploads/default-avatar.jpg', $path );
			
			# setting the query variables
			$fields = array ();
			$fields [] .= UsersService::ENABLED;
			$fields [] .= UsersService::AVATAR;
			$from = UsersService::USERS;
			$vals = array ();
			$vals [] .= '1';
			$vals [] .= $path;
			$where = UsersService::ID . " = '" . $id . "'";
			# executing the query
			DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
		}
	}

}


?>