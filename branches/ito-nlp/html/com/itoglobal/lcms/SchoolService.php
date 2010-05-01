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
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getSchoolsList($where = null) {
		$result = null;
		$fields = self::ID . ', ' . self::ALIAS . ', ' . self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::AVATAR . ', ' . self::RATE . ', ' . self::FEE;
		$from = self::SCHOOLS_TABLE;
		isset ( $where ) ? $where : '';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
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
		DBClientHandler::getInstance ()->execDelete ($from, $where, '', '' );
	}
	
	public static function validation($requestParams){
		$error = array ();
		$error [] .= self::checkAlias ( $requestParams [self::ALIAS] );
		$error [] .= self::checkName ( $requestParams [self::CAPTION] );
		$error [] .= self::checkDescription ( $requestParams [self::DESCRIPTION] );
		$error [] .= self::checkAdmin ( $requestParams [self::ADMIN] );
		return array_filter ( $error );
	}
	
	public static function checkAlias($alias) {
		$result = false;
		if (! $alias) {
			$result = 'Please enter alias';
		} else {
			$where = self::ALIAS . " = '" . $alias . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( self::ALIAS, self::SCHOOLS_TABLE, $where, '', '', '' );
			$result = isset ( $res [0] [self::ALIAS] ) ? 'Such alias already exists.' : false;
		}
		return $result;
	}
	public static  function checkName($name) {
		return $name ? false : 'Please enter caption';
	}
	public static  function checkDescription($description) {
		return $description ? false : 'Please enter description';
	}
	public static  function checkAdmin($admin) {
		$result = false;
		if (! $admin) {
				$result = 'Please enter admin';
		} else {
			$fields = UsersService::ID;
			$from = UsersService::USERS;
			$where = UsersService::USERNAME . "='" . $admin . "'";
			$res = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = isset ( $res [0] [UsersService::ID] ) ?  false : 'No such user';
		}
	return $result;
	}
}

?>