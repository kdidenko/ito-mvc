<?php
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class RegistrationController extends BaseActionControllerImpl {
	/**
	 * @var string defining the name of object
	 */
	const ERROR = 'error';
	
	public function registration($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		if (! isset ( $requestParams [UsersService::ID] )) {
			if ($_POST) {
				$error = array ();
				$error [] .= self::checkUsername ( $requestParams [UsersService::USERNAME] );
				$error [] .= self::checkName ( $requestParams [UsersService::FIRSTNAME] );
				$error [] .= self::checkLastname ( $requestParams [UsersService::LASTNAME] );
				$error [] .= self::checkEmail ( $requestParams [UsersService::EMAIL] );
				$error [] .= self::checkPassword ( $requestParams [UsersService::PASSWORD] );
				$error [] .= self::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] );
				//$error .= self::GenerateBirthday($birth_day, $birth_month, $birth_year);
				//TODO: create birthday field!
				

				$error = array_filter ( $error );
				
				if (count ( $error ) == 0) {
					// Insert new users to DB
					$fields = UsersService::USERNAME . ', ' . UsersService::FIRSTNAME . ', ' . UsersService::LASTNAME . ', ' . UsersService::EMAIL . ', ' . UsersService::PASSWORD . ', ' . UsersService::CRDATE . ', ' . UsersService::VALIDATION . ', ' . UsersService::ROLE;
					$hash = md5 ( rand ( 1, 9999 ) );
					$values = "'" . $requestParams [UsersService::USERNAME] . "','" . $requestParams [UsersService::FIRSTNAME] . "','" . $requestParams [UsersService::LASTNAME] . "','" . $requestParams [UsersService::EMAIL] . "','" . md5 ( $requestParams [UsersService::PASSWORD] ) . "','" . gmdate ( "Y-m-d H:i:s" ) . "','" . $hash . "','UR'";
					$into = UsersService::USERS;
					$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
					self::sendRegistrationMail ( $requestParams [UsersService::EMAIL], $hash, $requestParams [UsersService::USERNAME], $requestParams [UsersService::FIRSTNAME], $requestParams [UsersService::LASTNAME], $actionParams->property ['value'] );
					$location = $this->onSuccess ( $actionParams );
					$this->forwardActionRequest ( $location );
				} else {
					$mvc->addObject ( self::ERROR, $error );
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
				if (isset ( $requestParams [UsersService::EMAIL] ) && $requestParams [UsersService::EMAIL] != NULL) {
					$fields = UsersService::VALIDATION;
					$from = UsersService::USERS;
					$hash = md5 ( rand ( 1, 9999 ) );
					$vals = "'" . $hash . "'";
					$where = UsersService::EMAIL . " = '" . $requestParams [UsersService::EMAIL] . "'";
					DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
					
					$fields = UsersService::FIRSTNAME . ',' . UsersService::LASTNAME;
					$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
					if (isset ( $result [0] [UsersService::FIRSTNAME] )) {
						$email = $requestParams [UsersService::EMAIL];
						$subject = 'Please, confirm change password';
						$headers = 'From: YouCademy noreply@' . $_SERVER ['SERVER_NAME'];
						$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/new-password.html?email=' . $requestParams [UsersService::EMAIL] . '&validation_id=' . $hash;
						$vars ['###FIRST_NAME###'] = $result [0] [UsersService::FIRSTNAME];
						$vars ['###LAST_NAME###'] = $result [0] [UsersService::LASTNAME];
						$vars ['###CONFIRMATION_URL###'] = $url;
						$path = $actionParams->property ['value'];
						
						self::sendMail($path, $vars, $email, $subject, $headers);
						
						$location = $this->onSuccess ( $actionParams );
						$this->forwardActionRequest ( $location );
					}
				}
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
					$mvc->addObject ( self::ERROR, $error );
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
	
	private function sendRegistrationMail($email, $hash, $user, $firstname, $lastname, $path) {
		$fields = UsersService::ID;
		$from = UsersService::USERS;
		$where = UsersService::USERNAME . " = '" . $user . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$headers = 'From: YouCademy noreply@' . $_SERVER ['SERVER_NAME'];
		$subject = 'Please, confirm registration';
		$url = 'http://' . $_SERVER ['SERVER_NAME'] . '/confirm-registration.html?id=' . $result [0] [UsersService::ID] . '&validation_id=' . $hash;
		
		$vars ['###FIRST_NAME###'] = $firstname;
		$vars ['###LAST_NAME###'] = $lastname;
		$vars ['###CONFIRMATION_URL###'] = $url;
		$vars ['###USERNAME###'] = $user;
		
		self::sendMail($path, $vars, $email, $subject, $headers);
		
		return true;
	}
	
	private function sendMail($path, $vars, $email, $subject, $headers){
		$message = TemplateEngine::doPlain ( $path, $vars );
		mail ( $email, $subject, $message, $headers );	
	}
		
	private function checkUsername($username) {
		$result = false;
		if (! $username) {
			$result = 'Please enter your username';
		} else {
			$where = UsersService::USERNAME . " = '" . $username . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( UsersService::USERNAME, UsersService::USERS, $where, '', '', '' );
			$result = isset ( $res [0] [UsersService::USERNAME] ) ? 'Such username already exists.' : false;
		}
		return $result;
	}
	
	private function checkName($name) {
		return $name ? false : 'Please enter your First Name';
	}
	
	private function checkLastname($lastname) {
		return $lastname ? false : 'Please enter your Last Name';
	}
	
	private function checkEmail($email) {
		$result = false;
		if ($email) {
			if (! preg_match ( '/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email )) {
				$result = "Wrong email. Please enter a correct email";
			} else {
				$where = UsersService::EMAIL . " = '" . $email . "'";
				$res = DBClientHandler::getInstance ()->execSelect ( UsersService::EMAIL, UsersService::USERS, $where, '', '', '' );
				$result = isset ( $res [0] [UsersService::EMAIL] )? 'Such email already exists.' : false;
			}
		} else {
			$result = 'Please enter your email address.';
		}
		return $result;
	}
	
	private function checkPassword($password) {
		$result = false;
		if ($password) {
			$result = strlen ( $password ) < 6 ? 'The password you provided must have at least 6 characters.' : false;
		} else {
			$result = 'Please enter password';
		}
		return $result;
	}
	
	private function checkConfirmPassword($password, $confirm_password) {
		$result = false;
		if ($confirm_password) {
			$result = $password != $confirm_password ? 'Confirm Password does not match the password.' : false;
		} else {
			$result = 'Please enter confirm password';
		}
		return $result;
	}
	
	public function confirmRegistration($id, $hash) {
		# setting the query variables
		$fields = UsersService::VALIDATION . ',' . UsersService::USERNAME;
		$from = UsersService::USERS;
		$where = UsersService::ID . " = '" . $id . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		
		if ($result [0] [UsersService::VALIDATION] == $hash) {
			StorageService::createDirectory('storage/uploads/' . $result[0][UsersService::USERNAME]);
			StorageService::createDirectory('storage/uploads/' . $result[0][UsersService::USERNAME] . '/profile');
			StorageService::createDirectory('storage/uploads/' . $result[0][UsersService::USERNAME] . '/trainings');
			$path = 'storage/uploads/' . $result[0][UsersService::USERNAME] . '/profile/avatar.jpg';
			copy('storage/uploads/default-avatar.jpg', $path);
			
			# setting the query variables
			$fields = array();
			$fields[] .= UsersService::ENABLED;
			$fields[] .= UsersService::AVATAR;
			$from = UsersService::USERS;
			$vals = array();
			$vals[] .= '1';
			$vals[] .= $path;
			$where = UsersService::ID . " = '" . $id . "'";
			# executing the query
			DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
		}
	}

}

?>