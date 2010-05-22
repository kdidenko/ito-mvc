<?php

class CourseService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the schools table name
	 */
	const COURSE_TABLE = 'courses';
	/**
	 * @var  string defining the caption field name
	 */
	const CAPTION = 'caption';
	/**
	 * @var  string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var string defining the level field name
	 */
	const LEVEL = 'level';
	/**
	 * @var  string defining the alias field name
	 */
	const ALIAS = 'alias';
	/**
	 * @var  string defining the avatar field name
	 */
	const AVATAR = 'avatar';
	/**
	 * @var string defining the admin field name
	 */
	const SCHOOL_ID = 'school_id';
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
	 * @var string defining the remove
	 */
	const REMOVE = 'remove';
	/**
	 * @var string defining the add
	 */
	const ADD = 'add';
	/**
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getCoursesList($where = null, $limit = null) {
		$result = null;
		$fields = self::ID . ', ' . self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::LEVEL . ', ' . self::ALIAS . ', ' . self::AVATAR . ', ' . self::RATE . ', ' . self::FEE . ', ' . self::SCHOOL_ID;
		$from = self::COURSE_TABLE;
		isset ( $where ) ? $where : '';
		isset ( $limit ) ? $limit : '';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', $limit );
		return $result;
	}
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::COURSE_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteCourse($id) {
		# setting the query variables
		$from = self::COURSE_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete ( $from, $where, '', '' );
	}
	
	public static function removeCourse($id, $school_id) {
		# setting the query variables
		$fields = self::SCHOOL_ID;
		$vals = '0';
		$from = self::COURSE_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function addCourse($id, $school_id) {
		# setting the query variables
		$fields = self::SCHOOL_ID;
		$vals = $school_id;
		$from = self::COURSE_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
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
			$res = DBClientHandler::getInstance ()->execSelect ( self::ALIAS, self::COURSE_TABLE, $where, '', '', '' );
			$result = isset ( $res [0] [self::ALIAS] ) ? 'Such alias already exists.' : false;
		}
		return $result;
	}
}
?>