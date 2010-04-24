<?php
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	
	private static $validation = '';
	
	private static $email = '';
	
	const ID = 'id';
	
	const USERNAME = 'username';
	
	const FIRSTNAME = 'firstname';
	
	const LASTNAME = 'lastname';
	
	const EMAIL = 'email';
	
	const PASSWORD = 'password';
	
	const CONFIRM = 'confirm_password';
	
	const CRDATE = 'crdate';
	/**
	 * @var string defining the enabled field name
	 */
	const ENABLED = 'enabled';
	/**
	 * @var string defining the validation field name
	 */
	const VALIDATION = 'validation_id';
	
	const USERS = 'users';
	
	const ERROR = 'error';
	
	public function registration($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (! isset ( $requestParams [self::ID] )) {
			if ($_POST) {
				$error = array ();
				$error [] .= self::checkUsername ( $requestParams [self::USERNAME] );
				$error [] .= self::checkName ( $requestParams [self::FIRSTNAME] );
				$error [] .= self::checkLastname ( $requestParams [self::LASTNAME] );
				$error [] .= self::checkEmail ( $requestParams [self::EMAIL] );
				$error [] .= self::checkPassword ( $requestParams [self::PASSWORD] );
				$error [] .= self::checkConfirmPassword ( $requestParams [self::PASSWORD], $requestParams [self::CONFIRM] );
				//$error .= self::GenerateBirthday($birth_day, $birth_month, $birth_year);
				//TODO: create birthday field!
				

				$error = array_filter ( $error );
				
				if (count ( $error ) == 0) {
					// Insert new users to DB
					$fields = self::USERNAME . ', ' . self::FIRSTNAME . ', ' . self::LASTNAME . ', ' . self::EMAIL . ', ' . self::PASSWORD . ', ' . self::CRDATE . ', ' . self::VALIDATION;
					$hash = md5 ( rand ( 1, 9999 ) );
					$values = "'" . $requestParams [self::USERNAME] . "','" . $requestParams [self::FIRSTNAME] . "','" . $requestParams [self::LASTNAME] . "','" . $requestParams [self::EMAIL] . "','" . md5 ( $requestParams [self::PASSWORD] ) . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "'";
					$into = self::USERS;
					$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
					self::sendMail ( $requestParams [self::EMAIL], $hash, $requestParams [self::USERNAME], $requestParams [self::FIRSTNAME], $requestParams [self::LASTNAME], $actionParams->property ['value'] );
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					$mvc->addObject ( self::ERROR, $error );
				}
			}
		} else {
			self::confirmRegistration ( $requestParams [self::ID], $requestParams [self::VALIDATION] );
			$message = 'You completed registration.';
			$mvc->addObject ( 'message', $message );
		}
		return $mvc;
	
	}
	
	public function resetPassword($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		if (!isset ( $requestParams [self::PASSWORD] )) {
			if (!isset ( $requestParams [self::VALIDATION] )) {
				if (isset ( $requestParams [self::EMAIL] ) && $requestParams [self::EMAIL] != NULL) {
					$fields = self::VALIDATION;
					$from = self::USERS;
					$hash = md5 ( rand ( 1, 9999 ) );
					$vals = "'" . $hash . "'"; 
					$where = self::EMAIL . " = '" . $requestParams [self::EMAIL] . "'";
					DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
					
					$fields = self::FIRSTNAME . ',' . self::LASTNAME;
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					
					$email = $requestParams [self::EMAIL];
					$subject = 'Please, confirm change password';
					$headers = 'From: YouCademy noreply@' . $_SERVER ['SERVER_NAME'];
					$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [self::EMAIL] . '&validation_id=' . $hash;
					$vars ['###FIRST_NAME###'] = $result [0] [self::FIRSTNAME];
					$vars ['###LAST_NAME###'] = $result [0] [self::LASTNAME];
					$vars ['###CONFIRMATION_URL###'] = $url;
					$path = $actionParams->property ['value'];
					
					$message = TemplateEngine::doPlain ( $path, $vars );
					mail ( $email, $subject, $message, $headers );
					
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				}
			}else{
				$mvc->addObject(self::EMAIL, $requestParams[self::EMAIL]);
				$mvc->addObject(self::VALIDATION, $requestParams[self::VALIDATION]);
			}
		} else {
			$mvc->addObject(self::EMAIL, $requestParams[self::EMAIL]);
			$mvc->addObject(self::VALIDATION, $requestParams[self::VALIDATION]);
			# setting the query variables
			$fields = self::VALIDATION;
			$from = self::USERS;
			$where = self::EMAIL . " = '" . $requestParams[self::EMAIL] . "'";
			# executing the query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			if (isset($result [0] [self::VALIDATION]) && $result [0] [self::VALIDATION] == $requestParams[self::VALIDATION]) {
				$error = array ();
				$error [] .= self::checkPassword ( $requestParams [self::PASSWORD] );
				$error [] .= self::checkConfirmPassword ( $requestParams [self::PASSWORD], $requestParams [self::CONFIRM] );
				$error = array_filter ( $error );
				if (count ( $error ) == 0) {
					self::createNewPassword ( $requestParams[self::EMAIL], $requestParams [self::PASSWORD] );
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					$mvc->addObject ( self::ERROR, $error );
				}
			}else{
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		}
		return $mvc;
	}
	
	private function createNewPassword($email, $password) {
		$fields = self::PASSWORD;
		$from = self::USERS;
		$vals = "'" . md5 ( $password ) . "'";
		$where = self::EMAIL . " = '" . $email . "'";
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	private function sendMail($email, $hash, $user, $firstname, $lastname, $path) {
		$fields = self::ID;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $user . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$headers = 'From: YouCademy noreply@' . $_SERVER ['SERVER_NAME'];
		$subject = 'Please, confirm registration';
		$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/confirm-registration.html?id=' . $result [0] [self::ID] . '&validation_id=' . $hash;
		
		$vars ['###FIRST_NAME###'] = $firstname;
		$vars ['###LAST_NAME###'] = $lastname;
		$vars ['###CONFIRMATION_URL###'] = $url;
		$vars ['###USERNAME###'] = $user;
		
		$message = TemplateEngine::doPlain ( $path, $vars );
		mail ( $email, $subject, $message, $headers );
		return true;
	}
	
	private function checkUsername($username) {
		$result = false;
		if (! $username) {
			$result = 'Please enter your username';
		} else {
			$where = self::USERNAME . " = '" . $username . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( self::USERNAME, self::USERS, $where, '', '', '' );
			if (isset ( $res [0] [self::USERNAME] )) {
				$result = 'Such username already exists.';
			}
		}
		return $result;
	}
	
	private function checkName($name) {
		$result = false;
		if (! $name) {
			$result = 'Please enter your First Name';
		}
		return $result;
	}
	
	private function checkLastname($lastname) {
		$result = false;
		if (! $lastname) {
			$result = 'Please enter your Last Name';
		}
		return $result;
	}
	
	private function checkEmail($email) {
		$result = false;
		if ($email) {
			if (! preg_match ( '/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email )) {
				$result = "Wrong email. Please enter a correct email";
			} else {
				$where = self::EMAIL . " = '" . $email . "'";
				$res = DBClientHandler::getInstance ()->execSelect ( self::EMAIL, self::USERS, $where, '', '', '' );
				if (isset ( $res [0] [self::EMAIL] )) {
					$result = 'Such email already exists.';
				}
			}
		} else {
			$result = 'Please enter your email address.';
		}
		return $result;
	}
	
	private function checkPassword($password) {
		$result = false;
		if ($password) {
			if (strlen ( $password ) < 6) {
				$result = 'The password you provided must have at least 6 characters.';
			}
		} else {
			$result = 'Please enter password';
		}
		return $result;
	}
	
	private function checkConfirmPassword($password, $confirm_password) {
		$result = false;
		if ($confirm_password) {
			if ($password != $confirm_password) {
				$result = 'Confirm Password does not match the password.';
			}
		} else {
			$result = 'Please enter confirm password';
		}
		return $result;
	}
	
	public function confirmRegistration($id, $hash) {
		# setting the query variables
		$fields = self::VALIDATION;
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		
		if ($result [0] [self::VALIDATION] == $hash) {
			# setting the query variables
			$fields = self::ENABLED;
			$from = self::USERS;
			$vals = '1';
			$where = self::ID . " = '" . $id . "'";
			# executing the query
			DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
		}
	}

}

?>