<?php

class SchoolService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the schools table name
	 */
	const SCHOOLS_TABLE = 'schools';
	/**
	 * @var  string defining the alias field name
	 */
	const ALIAS = 'alias';
	/**
	 * @var  string defining the caption field name
	 */
	const CAPTION = 'caption';
	/**
	 * @var  string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var  string defining the avatar field name
	 */
	const AVATAR = 'avatar';
	/**
	 * @var string defining the admin field name
	 */
	const ADMIN = 'admin_id';
	/**
	 * @var string defining the fee_id field name
	 */
	const FEE = 'fee_id';
	/**
	 * @var string defining the enabled field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var ustring defining the rate field name
	 */
	const RATE = 'rate';
	/**
	 * @var string defining the deleted field name
	 */
	const DELETED = 'deleted';
	/**
	 * @var string defining the language field name
	 */
	const LANGUAGE = 'language';
	/**
	 * @var string defining the language field name
	 */
	const COURSES = 'courses';
	/**
	 * @var string defining the enabled field name
	 */
	const ENABLED = 'enabled';
	/**
	 * @var string defining the enabled field name
	 */
	const DISABLE = 'disable';
	/**
	 * @var string defining the old admin ield name
	 */
	const OLD_ADMIN = 'old_admin';
	/**
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getSchoolsList($where = null, $limit = null, $orderBy = null) {
		$result = null;
		$fields = self::SCHOOLS_TABLE . '.' .  self::ID . ', ' . self::ALIAS . ', ' . 
					self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::SCHOOLS_TABLE . '.' .  
					self::AVATAR . ', ' . self::RATE . ', ' . self::SCHOOLS_TABLE . '.' .  
					self::CRDATE . ', ' . self::FEE . ', ' . self::LANGUAGE . ', ' . 
					self::SCHOOLS_TABLE . '.' .  self::ENABLED . ', ' . UsersService::USERS . '.' .  
					UsersService::USERNAME . ', ' . self::SCHOOLS_TABLE . '.' .  self::ADMIN;
		$from = self::SCHOOLS_TABLE . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
					UsersService::USERS . '.' .  UsersService::ID . '=' . 
					self::SCHOOLS_TABLE . '.' . self::ADMIN;
		$where = isset ( $where ) ? self::SCHOOLS_TABLE . '.' .  $where : '';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', $orderBy, $limit );
		return $result;
	}
	
	public static function countStudents(){
		/*SELECT schools.id, schools.caption, COUNT(schools_assigned.school_id) as students
		FROM schools JOIN schools_assigned ON schools_assigned.school_id=schools.id
		WHERE schools_assigned.school_id=2
		GROUP BY schools_assigned.school_id*/
	}
	
	public static function countCourses(){
		/*SELECT schools.id, schools.caption, COUNT(courses.school_id) as students
		FROM schools JOIN courses ON courses.school_id=schools.id
		WHERE courses.school_id=2
		GROUP BY courses.school_id*/
	}
	
	
	
	public static function getSchool($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::SCHOOLS_TABLE . '.' .  self::ID . ', ' . self::ALIAS . ', ' . 
						self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::SCHOOLS_TABLE . '.' .  
						self::AVATAR . ', ' . self::RATE . ', ' . self::SCHOOLS_TABLE . '.' .  
						self::CRDATE . ', ' . self::FEE . ', ' . self::LANGUAGE . ', ' . 
						self::SCHOOLS_TABLE . '.' .  self::ENABLED . ', ' . UsersService::USERS . '.' .  
						UsersService::USERNAME . ', ' . self::SCHOOLS_TABLE . '.' .  self::ADMIN;
			$from = self::SCHOOLS_TABLE . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
						UsersService::USERS . '.' .  UsersService::ID . '=' . 
						self::SCHOOLS_TABLE . '.' . self::ADMIN;
			$where = self::SCHOOLS_TABLE . '.' . self::ID . '=' . $id;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;
		} 
		return $result;
	}	
	
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::SCHOOLS_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteSchool($id) {
		# setting the query variables
		$from = self::SCHOOLS_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete ( $from, $where, '', '' );
	}
	
	public static function validation($requestParams,  $_FILES) {
		$error = array ();
		$error [] .= self::checkAlias ( $requestParams [self::ALIAS] );
		$error [] .= $requestParams [self::CAPTION] ? false : 'Please enter caption';
		$error [] .= $requestParams [self::DESCRIPTION] ? false : 'Please enter description';
		/*$error [] .= self::checkAdmin ( $requestParams [self::ADMIN] );*/
		$error [] .= $_FILES ['file'] ['error'] == 0 ? ValidationService::checkAvatar( $_FILES ['file'] ) : false;
		return array_filter ( $error );
	}
	
	public static function checkAlias($alias) {
		$result = false;
		if (! $alias) {
			$result = 'Please enter alias';
		} else {
			$result = ValidationService::alphaNumeric ( $alias ) ? 
				false : 
					'Wrong alias. Please enter a correct email';
		}
		if (! $result) {
			$where = self::ALIAS . " = '" . $alias . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( self::ALIAS, self::SCHOOLS_TABLE, $where, '', '', '' );
			$result = isset ( $res [0] [self::ALIAS] ) ? 'Such alias already exists.' : false;
		}
		return $result;
	}

	/*public static function checkAdmin($admin) {
		$result = false;
		if (! $admin) {
			$result = 'Please enter admin';
		} else {
			$fields = UsersService::ID;
			$from = UsersService::USERS;
			$where = UsersService::USERNAME . "='" . $admin . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = isset ( $res [0] [UsersService::ID] ) ? false : 'No such user';
		}
		return $result;
	}*/
	
	
}

?>