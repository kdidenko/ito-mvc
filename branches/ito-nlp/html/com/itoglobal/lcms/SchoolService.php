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
	 * @var string defining the base_fee field name
	 */
	const BASE_FEE = 'base_fee';
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
	 * @var string defining the old admin field name
	 */
	const OLD_ADMIN = 'old_admin';
	/**
	 * @var string defining the students field name
	 */
	const CNT_STUDENTS = 'students';
	/**
	 * @var string defining the courses field name
	 */
	const CNT_COURSES = 'courses';
	/**
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getSchoolsList($where = null, $limit = null, $orderBy = null, $id = null) {
	/* generation sql query
	SELECT t1.*, COUNT(c.id) as courses FROM
	 (SELECT s.id, s.alias, s.caption AS caption, s.description, s.avatar, s.rate, s.crdate, 
	   s.fee_id, s.language, s.enabled, u.username AS moderator, COUNT(a.user_id) AS users
	  FROM `schools` AS s
	  LEFT JOIN schools_assigned AS a ON a.school_id = s.id
	  LEFT JOIN users AS u ON s.admin_id = u.id
	  GROUP BY s.id
	 ) AS t1
	LEFT JOIN courses AS c ON c.school_id = t1.id
	GROUP BY t1.id 
	*/
		$result = null;
		#creating table1
		$fields = self::SCHOOLS_TABLE . '.' .  self::ID . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::ALIAS . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::CAPTION . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::DESCRIPTION . ', ' . 
					self::SCHOOLS_TABLE . '.' .	self::AVATAR . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::RATE . ', ' . 
					self::SCHOOLS_TABLE . '.' .	self::CRDATE . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::BASE_FEE . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::LANGUAGE . ', ' . 
					self::SCHOOLS_TABLE . '.' . self::ENABLED . ', ' . 
					UsersService::USERS . '.' .	UsersService::USERNAME . ', ' . 
					self::SCHOOLS_TABLE . '.' .  self::ADMIN . ', ' . 
					SQLClient::COUNT . '(' . AssignmentsService::SCHOOLS_ASSIGNED . '.' . 
					AssignmentsService::USER_ID . ')' . SQLClient::SQL_AS . self::CNT_STUDENTS;
		$from = self::SCHOOLS_TABLE . SQLClient::LEFT . SQLClient::JOIN . 
				AssignmentsService::SCHOOLS_ASSIGNED . SQLClient::ON . 
				AssignmentsService::SCHOOLS_ASSIGNED . '.' . AssignmentsService::SCHOOL_ID . '=' . 
				self::SCHOOLS_TABLE . '.' . self::ID . SQLClient::LEFT . SQLClient::JOIN .
				UsersService::USERS . SQLClient::ON . self::SCHOOLS_TABLE . '.' . self::ADMIN . '=' .
				UsersService::USERS . '.' .  UsersService::ID;
		$groupBy = self::SCHOOLS_TABLE . '.' . self::ID;
		$table = '(' . SQLClient::SELECT . $fields . SQLClient::FROM . $from;
		#if isset param $where
		$table .= isset ( $where ) ? SQLClient::WHERE . self::SCHOOLS_TABLE . '.' .  $where : NULL;
		#if isset param $id
		$table .= isset ($id) && $id != NULL ? SQLClient::WHERE . self::SCHOOLS_TABLE . '.' . self::ID . '=' . $id : NULL;
		$table .= SQLClient::GROUOPBY . $groupBy;
		#if isset param $limit
		$table .= isset ( $limit ) ? SQLClient::LIMIT . $limit : NULL;
		$table .= ')' . SQLClient::SQL_AS . 't1';
		#creating full sql query
		$fields = 't1.*, ' . SQLClient::COUNT . '(' . CourseService::COURSE_TABLE . '.' . 
					CourseService::ID . ')' . SQLClient::SQL_AS . self::CNT_COURSES;
		$from = $table . SQLClient::LEFT . SQLClient::JOIN . 
				CourseService::COURSE_TABLE . SQLClient::ON . 
				CourseService::COURSE_TABLE . '.' . CourseService::SCHOOL_ID . '=' . 't1.' . self::ID;
		$groupBy = 't1.' . self::ID;
		$sql = SQLClient::SELECT . $fields . SQLClient::FROM . $from . SQLClient::GROUOPBY . $groupBy;
		$sql .= isset ( $orderBy ) ? SQLClient::ORDERBY . 't1' . '.' .  $orderBy : NULL;
		$result = DBClientHandler::getInstance ()->exec  ( $sql );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : null;
		return $result;
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

}

?>