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
	 * @var string defining the salutation field name
	 */
	const SALUTATION = 'salutation';
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
	 * @var string defining the old_password field name
	 */
	const OLDPASSWORD = 'old_password';
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
	 * @var string defining the enabled field name
	 */
	const DISABLE = 'disable';
	/**
	 * @var string defining the deleted field name
	 */
	const DELETED = 'deleted';
	/**
	 * @var string defining the validation field name
	 */
	const VALIDATION = 'validation_id';
	/**
	 * @var string defining the birthday field name
	 */
	const BIRTHDAY = 'birthday';
	
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
	 * @var string defining the Moderator role type
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
	/**
	 * @var string defining the name of object
	 */
	const ON_SUCCESS = 'on success';
	/**
	 * @var string defining the name of object
	 */
	const AGREEMENT = 'agreement';
	
	public static function getUsersList($where = NULL) {
		$fields = self::USERS . '.*';
		$from = self::USERS;
		$groupBy = self::USERS . '.' . self::ID;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy , '', '' );
		//$result = $result != null && isset($result) && count($result) == 1 ? $result[0] : $result;
		return $result;
	}	
	/**
	 * Retreives the user data by specified user id.
	 * @param integer $id the user id.
	 * @return mixed user data or null if user with such id does not exists. 
	 */
	public static function getUser($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::USERS . '.*';
			$from = self::USERS;
			$where = self::USERS . '.' . self::ID . '=' . $id;
			$groupBy = self::USERS . '.' . self::ID;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;
		} 
		return $result;
	}
	
	public function getUserIdByName ($username){
		$result = null;		
		if(isset($username) && $username != ''){
			# preparing query
			$fields = self::USERS . '.' . self::ID;
			$from = self::USERS;
			$where = self::USERNAME . "='" . $username . "'";
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result[0][UsersService::ID] : null;
		} 
		return $result;
	}
	
	public static function countUsers ($where = NULL){
		$fields =  SQLClient::COUNT . "(" . self::USERS . '.' . self::ID . ") as " . self::USERS; 
		$from = self::USERS . 
				SQLClient::LEFT . SQLClient::JOIN . PlanService::PLAN .	SQLClient::ON . 
				PlanService::PLAN . '.' . PlanService::ID . '=' . 
				self::USERS . '.' . self::PLAN_ID;
		$groupBy = self::USERS . '.' . self::ID;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	/**
	 * Retreives the user data by specified database field.
	 * @param text $field the field which creating character scroller.
	 * @param text $role
	 * @param text $NoRole
	 * @return mixed user data or null if users does not exists. 
	 */
	public static function chrScroller($field, $role = NULL, $NoRole = NULL){
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$fields = "DISTINCT UCASE(" . SQLClient::LEFT . "(" . $field . ", 1 ) ) AS scroller";
		$from = self::USERS;
		#without present user 
		$where = UsersService::ID . "!=" . $id . ' AND ' . UsersService::DELETED . '=0';
		#only for one user role
		$where .= $role != NULL ? " AND " . UsersService::ROLE . "='" . $role . "'": NULL;
		#without some user role
		$where .= $NoRole != NULL ? " AND " . UsersService::ROLE . SQLClient::NOT . SQLClient::IN . 
									"(" . $NoRole . ")" : 
														NULL;
		$orderBy = self::LASTNAME;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', $orderBy, '' );
		$result = $result!= null && count($result) >0 ? $result : null ;
		return $result;
	}
	
	/**
	 * Update user data
	 * @param int $id user ID
	 * @param array $fields field which should be update
	 * @param array $vals values for fields
	 */
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteUser($id) {
		# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		#DBClientHandler::getInstance ()->execDelete ( $fields, $from, $vals, $where, '', '' );
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}
	/*public static function createUserDirectory($directory){
		StorageService::createDirectory ( $user_path . $directory );
		StorageService::createDirectory ( $user_path . $directory . '/profile' );
		StorageService::createDirectory ( $user_path . $directory . '/courses' );
		$path = $user_path . $directory . '/profile/avatar.jpg';
		copy ( $user_avatar, $path );
		return $path;
	}*/
	
	public static function validation($requestParams, $_FILES = null){
		$error = array ();
		$error [] .= isset($requestParams [self::USERNAME])? self::checkUsername ( $requestParams [self::USERNAME] ) : false;
		$error [] .= self::checkEmail ( $requestParams [self::EMAIL] );
		$error [] .= isset($requestParams [self::PASSWORD])? self::checkPassword ( $requestParams [self::PASSWORD] ) : false;
		$error [] .= isset($requestParams [self::CONFIRM])? self::checkConfirmPassword ( $requestParams [self::PASSWORD], $requestParams [self::CONFIRM] ) : false;
		$error [] .= isset($requestParams [self::AGREEMENT]) ? false : '_i18n{Please, check user agreement.}';
		if(isset($_FILES)) {
			$error [] .= $_FILES ['file'] ['error'] == 0 ? ValidationService::checkAvatar( $_FILES ['file'] ) : false;
		}
		return array_filter ( $error );
	}
	
	public static function checkUsername($username) {
		$result = false;
		if (! $username) {
			$result = '_i18n{Please, enter your username.}';
		} else {
			$result = ValidationService::alphaNumeric($username)?
				false :
					'_i18n{Wrong username. Please, enter a correct username.}';
		}
		if (!$result) {
			$where = self::USERNAME . " = '" . $username . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( self::USERNAME, UsersService::USERS, $where, '', '', '' );
			$result = isset ( $res [0] [self::USERNAME] ) ? '_i18n{Such username already exists.}' : false;
		}
		return $result;
	}
	
	public static  function checkEmail($email) {
		$result = false;
		if ($email) {
			$result = ValidationService::checkEmail($email);
			if (!$result) {
				$where = self::EMAIL . " = '" . $email . "'";
				$res = DBClientHandler::getInstance ()->execSelect ( self::EMAIL, self::USERS, $where, '', '', '' );
				$result = isset ( $res [0] [self::EMAIL] )? '_i18n{Such email already exists.}' : false;
			}
		} else {
			$result = '_i18n{Please, enter your email address.}';
		}
		return $result;
	}
	
	public static  function checkPassword($password) {
		$result = false;
		$result = $password ?
			$result = ValidationService::checkPassword($password) :
				'_i18n{Please, enter password.}';
		return $result;
	}

	public static  function checkOldPassword($password) {
		$result = false;
		$result = $password ?
			$result = ValidationService::checkPassword($password) :
				'_i18n{Please, enter your old password.}';
		return $result;
	}
	
	public static  function checkConfirmPassword($password, $confirm_password) {
		$result = false;
		$result = $confirm_password ? 
			ValidationService::checkConfirmPassword($password, $confirm_password) :
				'_i18n{Please, enter confirm password.}';
		return $result;
	}
	

}

?>