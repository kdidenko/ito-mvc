<?php

class UsersService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the username field name
	 */
	const USERNAME = 'username';
	/**
	 * @var string defining the firstname field name
	 */
	const FIRSTNAME = 'firstname';
	/**
	 * @var string defining the lastname field name
	 */
	const LASTNAME = 'lastname';
	/**
	 * @var string defining the email field name
	 */
	const EMAIL = 'email';
	/**
	 * @var string defining the password field name
	 */
	const PASSWORD = 'password';
	/**
	 * @var string defining the confirm_password field name
	 */
	const CONFIRM = 'confirm_password';
	/**
	 * @var string defining the users table name
	 */
	const USERS = 'users';
	/**
	 * @var string defining the enabled field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the enabled field name
	 */
	const ENABLED = 'enabled';
	/**
	 * @var string defining the deleted field name
	 */
	const DELETED = 'deleted';
	/**
	 * @var string defining the validation field name
	 */
	const VALIDATION = 'validation_id';
	/**
	 * @var string defining the role field name
	 */
	const ROLE = 'role';
	/**
	 * @var string defining the visitor role type 
	 */
	const ROLE_VR = 'VR';
	/**
	 * @var string defining the user role type
	 */
	const ROLE_UR = 'UR';
	/**
	 * @var string defining the moderator role type
	 */
	const ROLE_MR = 'MR';
	/**
	 * @var string defining the administrator role type
	 */
	const ROLE_AR = 'AR';
	/**
	 * @var string defining the avatar field name
	 */
	const AVATAR = 'avatar';
	/**
	 * @var string defining the name of object
	 */
	const ERROR = 'error';
	
	public static function getFields($where = null) {
		$fields = self::ID . ', ' . self::USERNAME . ', ' . SessionService::FIRSTNAME . ', ' . SessionService::LASTNAME . ', ' . SessionService::EMAIL . ', ' . self::ENABLED . ', ' . self::DELETED . ', ' . self::ROLE . ', ' . self::AVATAR;
		$from = self::USERS;
		isset ( $where ) ? $where : '';
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		return $result;
	}
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteUser($id, $fields, $vals) {
		# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function validation($requestParams){
		$error = array ();
		$error [] .= self::checkUsername ( $requestParams [UsersService::USERNAME] );
		$error [] .= self::checkName ( $requestParams [UsersService::FIRSTNAME] );
		$error [] .= self::checkLastname ( $requestParams [UsersService::LASTNAME] );
		$error [] .= self::checkEmail ( $requestParams [UsersService::EMAIL] );
		$error [] .= isset($requestParams [UsersService::PASSWORD])? self::checkPassword ( $requestParams [UsersService::PASSWORD] ) : false;
		$error [] .= isset($requestParams [UsersService::CONFIRM])? self::checkConfirmPassword ( $requestParams [UsersService::PASSWORD], $requestParams [UsersService::CONFIRM] ) : false;
		//$error .= self::GenerateBirthday($birth_day, $birth_month, $birth_year);
		//TODO: create birthday field!
		return array_filter ( $error );
	}
	
	public static function checkUsername($username) {
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
	
	public static  function checkName($name) {
		return $name ? false : 'Please enter your First Name';
	}
	
	public static  function checkLastname($lastname) {
		return $lastname ? false : 'Please enter your Last Name';
	}
	
	public static  function checkEmail($email) {
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
	
	public static  function checkPassword($password) {
		$result = false;
		if ($password) {
			$result = strlen ( $password ) < 6 ? 'The password you provided must have at least 6 characters.' : false;
		} else {
			$result = 'Please enter password';
		}
		return $result;
	}
	
	public static  function checkConfirmPassword($password, $confirm_password) {
		$result = false;
		if ($confirm_password) {
			$result = $password != $confirm_password ? 'Confirm Password does not match the password.' : false;
		} else {
			$result = 'Please enter confirm password';
		}
		return $result;
	}

}

?>