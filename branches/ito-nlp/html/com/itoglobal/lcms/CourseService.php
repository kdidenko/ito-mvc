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
	 * @var ustring defining the category_id field name
	 */
	const CATEGORY_ID = 'category_id';
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
	const COURSE_CAPTION = 'course_caption';
	/**
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getCoursesList($where = null, $limit = null, $orderBy = null) {
		$result = null;
		$fields = self::COURSE_TABLE . '.' . self::ID . ', ' . self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::CRDATE . ', ' . 
				self::ALIAS . ', ' . self::AVATAR . ', ' . self::RATE . ', ' . self::BASE_FEE . ', ' . 
				self::COURSE_TABLE . '.' . self::SCHOOL_ID . ', ' . self::CATEGORY_ID . ', ' . CategoriesService::CATEGORIES_TABLE . '.' . 
				CategoriesService::NAME;
		$from = self::COURSE_TABLE . SQLClient::LEFT . SQLClient::JOIN . 
				CategoriesService::CATEGORIES_TABLE . SQLClient::ON .
				CategoriesService::CATEGORIES_TABLE . '.' . CategoriesService::ID . '=' . 
				self::COURSE_TABLE . '.' . self::CATEGORY_ID ;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', $orderBy, $limit );
		return $result;
	}
	/*
	 $result = null;
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = self::COURSE_TABLE . '.' . self::ID . ', ' . self::COURSE_TABLE . '.' . self::CAPTION . ', ' . 
				self::COURSE_TABLE . '.' . self::DESCRIPTION . ', ' . self::COURSE_TABLE . '.' . self::CRDATE . ', ' . 
				self::COURSE_TABLE . '.' . self::ALIAS . ', ' . self::COURSE_TABLE . '.' . self::AVATAR . ', ' . 
				self::COURSE_TABLE . '.' . self::RATE . ', ' . self::COURSE_TABLE . '.' . self::BASE_FEE . ', ' . 
				self::COURSE_TABLE . '.' . self::SCHOOL_ID . ', ' . self::CATEGORY_ID . ', ' . 
				CategoriesService::CATEGORIES_TABLE . '.' .	CategoriesService::NAME;
		$from = self::COURSE_TABLE . SQLClient::LEFT . SQLClient::JOIN . 
				CategoriesService::CATEGORIES_TABLE . SQLClient::ON .
				CategoriesService::CATEGORIES_TABLE . '.' . CategoriesService::ID . '=' . 
				self::COURSE_TABLE . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SchoolService::SCHOOLS_TABLE . SQLClient::ON . 
				SchoolService::SCHOOLS_TABLE  . '.' . SchoolService::ID . '=' . 
				self::COURSE_TABLE . '.' . self::SCHOOL_ID;
		$where = $where != NULL ? $where . " AND ": NULL;
		$where .= SchoolService::SCHOOLS_TABLE  . '.' . SchoolService::ALIAS . "='" . $id . "'";
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', $orderBy, $limit );
		return $result;
	 */
	public static function getAccessMRcourses($where){
		#get courses list (assigned to moderator) for creating new exercise
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = CourseService::COURSE_TABLE . '.' . CourseService::ID . ', ' . CourseService::COURSE_TABLE . '.' . CourseService::CAPTION . ', ' . 
				CourseService::COURSE_TABLE . '.' . CourseService::DESCRIPTION . ', ' . CourseService::COURSE_TABLE . '.' . CourseService::CRDATE . ', ' . 
				CourseService::COURSE_TABLE . '.' . CourseService::ALIAS . ', ' . CourseService::COURSE_TABLE . '.' . CourseService::AVATAR . ', ' . 
				CourseService::COURSE_TABLE . '.' . CourseService::RATE . ', ' . CourseService::COURSE_TABLE . '.' . CourseService::BASE_FEE . ', ' . 
				CourseService::COURSE_TABLE . '.' . CourseService::SCHOOL_ID . ', ' . CourseService::CATEGORY_ID . ', ' . 
				CategoriesService::CATEGORIES_TABLE . '.' .	CategoriesService::NAME;
		$from = CourseService::COURSE_TABLE . SQLClient::JOIN . SchoolService::SCHOOLS_TABLE . 
				SQLClient::ON . SchoolService::SCHOOLS_TABLE . "." . SchoolService::ID . "=" .
				CourseService::COURSE_TABLE . "." . CourseService::SCHOOL_ID . 
				SQLClient::LEFT . SQLClient::JOIN . 
				CategoriesService::CATEGORIES_TABLE . SQLClient::ON .
				CategoriesService::CATEGORIES_TABLE . '.' . CategoriesService::ID . '=' . 
				CourseService::COURSE_TABLE . '.' . CourseService::CATEGORY_ID;
		$where .= SchoolService::ADMIN . " = '" . $user_id . "'";
		# executing query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$list = $result != null && isset($result) && count($result) > 0 ? $result : null;
		return $list;
	}
	
	#get list of access courses for user
	public static function getAccessCourses($where = NULL) {
		$sql = self::createQuery($where);
		$result = DBClientHandler::getInstance ()->exec ($sql);
		$result = isset ($result) || $result!= NULL ? $result : NULL;
		return $result;
	}
	private static function createQuery($where=NULL){
		/* sql query
			SELECT c.caption, c.id, s.caption FROM courses AS c
			LEFT JOIN schools AS s ON s.id=c.school_id
			LEFT JOIN schools_assigned AS a ON a.school_id=s.id
			WHERE a.user_id=40
		*/
		$id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = self::COURSE_TABLE . '.' . self::CAPTION . SQLClient::SQL_AS . self::COURSE_CAPTION . ', ' . self::COURSE_TABLE . '.' . self::ID . ', ' . 
				SchoolService::SCHOOLS_TABLE . '.' . SchoolService::CAPTION;
		$from = self::COURSE_TABLE;
		$join = SQLClient::LEFT . SQLClient::JOIN . SchoolService::SCHOOLS_TABLE . SQLClient::ON .
				SchoolService::SCHOOLS_TABLE . '.' . SchoolService::ID . '=' . 
				self::COURSE_TABLE . '.' . self::SCHOOL_ID . 
				SQLClient::LEFT . SQLClient::JOIN . AssignmentsService::SCHOOLS_ASSIGNED . SQLClient::ON . 
				AssignmentsService::SCHOOLS_ASSIGNED  . '.' . AssignmentsService::SCHOOL_ID . '=' . 
				SchoolService::SCHOOLS_TABLE . '.' . SchoolService::ID;
		$where = $where != NULL ? $where . " AND ": NULL;
		$where .= AssignmentsService::USER_ID . "='" . $id . "'";
		$sql = SQLClient::SELECT . $fields . SQLClient::FROM . $from . $join . 
				SQLClient::WHERE . $where; 
		return $sql;
	}
	#get list of access courses witch user don't sign in
	public static function getOtherCourses($t_index){
		/*
		 SELECT t1.* FROM
			(
			SELECT c.caption AS course_caption, c.id, s.caption FROM courses AS c
			LEFT JOIN schools AS s ON s.id=c.school_id
			LEFT JOIN schools_assigned AS a ON a.school_id=s.id
			WHERE a.user_id=37
			)
			AS t1
		LEFT JOIN trainings AS t ON t.course_id=t1.id
		WHERE t.course_id IS NULL
		 */
		$begin = SQLClient::SELECT . "t1.*" . SQLClient::FROM . '(';
		$sql = self::createQuery();
		$end = ')AS t1' . SQLClient::LEFT . SQLClient::JOIN . TrainingsService::TRAININGS_TABLE . 
				SQLClient::ON . TrainingsService::TRAININGS_TABLE . '.' . TrainingsService::COURSE_ID . '=' . 
				't1.' . CourseService::ID . SQLClient::WHERE . TrainingsService::TRAININGS_TABLE . '.' . 
				TrainingsService::COURSE_ID . SQLClient::IS_NULL;
		$sql = $begin . $sql . $end;
		$result = DBClientHandler::getInstance ()->exec ($sql);
		$result = isset ($result) || $result!= NULL ? $result : NULL;
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
	
	public static function validation($requestParams,  $_FILES) {
		$error = array ();
		$error [] .= self::checkAlias ( $requestParams [self::ALIAS] );
		$error [] .= $requestParams [self::CAPTION] ? false : 'Please enter caption.';
		$error [] .= $requestParams [self::DESCRIPTION] ? false : 'Please enter description.';
		$error [] .= $requestParams [self::CATEGORY_ID]!=0 ? false : 'First, please, you must create a category.';
		$error [] .= $_FILES ['file'] ['error'] == 0 ? ValidationService::checkAvatar( $_FILES ['file'] ) : false;
		return array_filter ( $error );
	}
	public static function checkAlias($alias) {
		$result = false;
		if (! $alias) {
			$result = 'Please enter alias.';
		} else {
			$result = ValidationService::alphaNumeric ( $alias ) ? 
				false : 
					'Wrong alias. Please enter a correct alias.';
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