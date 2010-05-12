<?php

class ExerciseService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the exercises table name
	 */
	const EXERCISES_TABLE = 'exercises';
	/**
	 * @var  string defining the caption field name
	 */
	const CAPTION = 'caption';
	/**
	 * @var  string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var string defining the create date field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the owner id field name
	 */
	const OWNER = 'owner_id';
	/**
	 * @var string defining the course_id field name
	 */
	const COURSE_ID = 'course_id';
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
	public static function getExercisesList($where = null, $limit = null) {
		$result = null;
		$fields = self::ID . ', ' . self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::OWNER . ', ' . self::RATE . ', ' . self::COURSE_ID;
		$from = self::EXERCISES_TABLE;
		isset ( $where ) ? $where : '';
		isset ( $limit ) ? $limit : '';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', $limit );
		$fields = UsersService::USERNAME;
		
		$from = UsersService::USERS;
		for($i=0;$i<count($result);$i++){
			$where = $result[$i][self::OWNER] . '=' . UsersService::ID;
			$res = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result[$i][self::OWNER] = $res[0][UsersService::USERNAME];
		}
				
		return $result;
	}
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::EXERCISES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteExercise($id) {
		# setting the query variables
		$from = self::EXERCISES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete ($from, $where, '', '' );
	}
	
	public static function removeExercise($id, $course_id) {
		# setting the query variables
		$fields = self::COURSE_ID;
		$vals = '0';
		$from = self::EXERCISES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function addExercise($id, $course_id) {
		# setting the query variables
		$fields = self::COURSE_ID;
		$vals = $course_id;
		$from = self::EXERCISES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	
	public static function validation($requestParams){
		$error = array ();
		$error [] .= self::checkName ( $requestParams [self::CAPTION] );
		$error [] .= self::checkDescription ( $requestParams [self::DESCRIPTION] );
		return array_filter ( $error );
	}
	public static  function checkName($name) {
		return $name ? false : 'Please enter exercise name';
	}
	public static  function checkDescription($description) {
		return $description ? false : 'Please enter description';
	}
}

?>