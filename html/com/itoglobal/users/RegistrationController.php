<?php
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	
	public static $error = array ();
	
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
				self::$error [] .= self::checkUsername ( $requestParams [self::USERNAME] );
				self::$error [] .= self::checkName ( $requestParams [self::FIRSTNAME] );
				self::$error [] .= self::checkLastname ( $requestParams [self::LASTNAME] );
				self::$error [] .= self::checkEmail ( $requestParams [self::EMAIL] );
				self::$error [] .= self::checkPassword ( $requestParams [self::PASSWORD] );
				self::$error [] .= self::checkConfirmPassword ( $requestParams [self::PASSWORD], $requestParams [self::CONFIRM] );
				//self::$error .= self::GenerateBirthday($birth_day, $birth_month, $birth_year);
				//TODO: create birthday field!        
				

				if (self::$error [0] == NULL & self::$error [1] == NULL & self::$error [2] == NULL & self::$error [3] == NULL & self::$error [4] == NULL & self::$error [5] == NULL) {
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
					$mvc->addObject ( self::ERROR, self::$error );
				}
			}
		} else {
			self::confirmRegistration ( $requestParams [self::ID], $requestParams [self::VALIDATION] );
			$message = 'You completed registration. Your registration successful';
			$mvc->addObject ( 'message', $message );
		}
		return $mvc;
	
	}
	
	private function sendMail($email, $hash, $user, $firstname, $lastname, $path) {
		$fields = self::ID;
		$from = self::USERS;
		$where = self::USERNAME . " = '" . $user . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$headers = 'From: YouCademy noreply@' . $_SERVER ['SERVER_NAME'];
		$subject = 'Please confirm registration';
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
		if (! $username) {
			return 'Please enter your User Name';
		} else {
			$where = self::USERNAME . " = '" . $username . "'";
			$result = DBClientHandler::getInstance ()->execSelect ( self::USERNAME, self::USERS, $where, '', '', '' );
			if (isset ( $result [0] [self::USERNAME] )) {
				return 'There is an existing account associated with this User Name.';
				break;
			}
		}
	}
	
	private function checkName($name) {
		if (! $name) {
			return 'Please enter your First Name';
		}
	}
	
	private function checkLastname($lastname) {
		if (! $lastname) {
			return 'Please enter your Last Name';
		}
	}
	
	private function checkEmail($email) {
		if ($email) {
			if (! preg_match ( '/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email )) {
				return "Wrong email. Please enter a correct email";
			} else {
				$where = self::EMAIL . " = '" . $email . "'";
				$result = DBClientHandler::getInstance ()->execSelect ( self::EMAIL, self::USERS, $where, '', '', '' );
				if (isset ( $result [0] [self::EMAIL] )) {
					return 'There is an existing account associated with this email.';
					break;
				}
			}
		} else {
			return 'Please enter your email address.';
		}
	}
	
	private function checkPassword($password) {
		if ($password) {
			if (strlen ( $password ) < 6) {
				return 'The password you provided must have at least 6 characters.';
			}
		} else {
			return 'Please enter password';
		}
	}
	
	private function checkConfirmPassword($password, $confirm_password) {
		if ($confirm_password) {
			if ($password != $confirm_password) {
				return 'Confirm Password does not match the password.';
			}
		} else {
			return 'Please enter confirm password';
		}
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