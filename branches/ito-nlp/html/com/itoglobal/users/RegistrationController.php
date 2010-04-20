<?php
require_once 'com/itoglobal/db/sql/mysql/SQLClient.php';
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	
	public static $error = '';
	
	const ID = 'id';
	
	const USERNAME = 'username';
	
	const FIRSTNAME = 'firstname';
	
	const LASTNAME = 'lastname';
	
	const EMAIL = 'email';
	
	const PASSWORD = 'password';
	
	const CONFIRM = 'confirm_password';
	
	const CRDATE = 'crdate';
	
	const VALIDATION = 'validation_id';
	
	const USERS = 'users';
	
	public function registration($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if ($_POST) {
			self::$error = '';
			self::$error .= self::checkUsername ( $requestParams [self::USERNAME] );
			self::$error .= self::checkName ( $requestParams [self::FIRSTNAME] );
			self::$error .= self::checkLastname ( $requestParams [self::LASTNAME] );
			self::$error .= self::checkEmail ( $requestParams [self::EMAIL] );
			self::$error .= self::checkPassword ( $requestParams [self::PASSWORD] );
			self::$error .= self::checkConfirmPassword ( $requestParams [self::PASSWORD], $requestParams [self::CONFIRM] );
			//self::$error .= self::GenerateBirthday($birth_day, $birth_month, $birth_year);
			//TODO: create birthday field!        
			

			//TODO: error reporting
			//$error - mistakes
			if (self::$error == NULL) {
				// Insert new users to DB
				$fields = self::USERNAME . ', ' . self::FIRSTNAME . ', ' . self::LASTNAME . ', ' . self::EMAIL . ', ' . self::PASSWORD . ', ' . self::CRDATE . ', ' . self::VALIDATION;
				$hash = md5 ( rand ( 1, 9999 ) );
				$values = "'" . $requestParams [self::USERNAME] . "','" . $requestParams [self::FIRSTNAME] . "','" . $requestParams [self::LASTNAME] . "','" . $requestParams [self::EMAIL] . "','" . $requestParams [self::PASSWORD] . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "'";
				$into = self::USERS;
				$link = SQLClient::connect ( 'youcademy', 'localhost', 'root', '' );
				$result = SQLClient::execInsert ( $fields, $values, $into, $link );
				
				self::sendMail ( $requestParams [self::EMAIL], $hash, $requestParams [self::USERNAME] );
				$location = $this->onSuccess ( $actionParams );
				$this->forwardActionRequest ( $location );
			} else {
				$location = $this->onFailure ( $actionParams );
				$this->forwardActionRequest ( $location );
			}
		}
		return $mvc;
	
	}
	
	private function sendMail($email, $hash, $user) {
		$fields = self::ID;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $user . "'";
		$link = SQLClient::connect ( 'youcademy', 'localhost', 'root', '' );
		$result = SQLClient::execSelect ( $fields, $from, $where, '', '', '', $link );
		
		$subject = 'Confirm registration';
		$url = 'http://' . $_SERVER[SERVER_NAME] . '/validation.html?id=' . $result [0] [self::ID] . '&validation=' . $hash;
		$message = "Please click here " . $url;
		$headers  = 'From:' . $_SERVER[SERVER_NAME];
		mail ( $email, $subject, $message, $headers );
		return true;
	}
	
	private function checkUsername($username) {
		if (! $username) {
			return '<p>Please enter your User Name</p>';
		}
	}
	
	private function checkName($name) {
		if (! $name) {
			return '<p>Please enter your First Name</p>';
		}
	}
	
	private function checkLastname($lastname) {
		if (! $lastname) {
			return '<p>Please enter your Last Name</p>';
		}
	}
	
	private function checkEmail($email) {
		if ($email) {
			if (! preg_match ( '/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email )) {
				return "<p>Wrong email. Please enter a correct email</p>";
			}
		} else {
			return '<p>Please enter your email address.</p>';
		}
	}
	
	private function checkPassword($password) {
		if ($password) {
			if (strlen ( $password ) < 6) {
				return '<p>The password you provided must have at least 6 characters.</p>';
			}
		} else {
			return '<p>Please enter password</p>';
		}
	}
	
	private function checkConfirmPassword($password, $confirm_password) {
		if ($confirm_password) {
			if ($password != $confirm_password) {
				return '<p>Confirm Password does not match the password.</p>';
			}
		} else {
			return '<p>Please enter confirm password</p>';
		}
	}

}

?>