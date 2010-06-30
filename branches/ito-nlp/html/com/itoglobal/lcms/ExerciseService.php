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
	 * @var string defining the video field name
	 */
	const VIDEO = 'video';
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
		# get the exercises list
		$result = null;
		$fields = self::EXERCISES_TABLE . '.' .  self::ID . ', ' . self::CAPTION . ', ' . 
					self::DESCRIPTION . ', ' . self::OWNER . ', ' . self::RATE . ', ' . self::COURSE_ID . ', ' . 
					self::VIDEO . ', ' . UsersService::USERS . '.' .  UsersService::USERNAME;
		$from = self::EXERCISES_TABLE . SQLClient::JOIN . UsersService::USERS . 
				SQLClient::ON . UsersService::USERS . '.' .  UsersService::ID . '=' . 
				self::EXERCISES_TABLE . '.' . self::OWNER;
		$where = isset ( $where ) ? self::EXERCISES_TABLE . '.' .  $where : '';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', $limit );
		return $result;
	}
	
	public static function getAccessEx($id, $where = NULL) {
		/* sql query
			SELECT e.caption, e.id
			FROM exercises AS e
			LEFT JOIN trainings AS t ON t.course_id = e.course_id
			WHERE t.user_id = " .  $id . " GROUP BY e.id
		*/
		$fields = ExerciseService::EXERCISES_TABLE . '.*';
		$from = ExerciseService::EXERCISES_TABLE;
		$join = SQLClient::LEFT . SQLClient::JOIN . TrainingsService::TRAININGS_TABLE . SQLClient::ON .
				TrainingsService::TRAININGS_TABLE . '.' . TrainingsService::COURSE_ID . '=' . 
				ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::COURSE_ID;
		$where = $where != NULL ? $where . " AND ": NULL;
		$where .= TrainingsService::USER_ID . "='" . $id . "'";
		$groupBy = ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::ID;
		$sql = SQLClient::SELECT . $fields . SQLClient::FROM . $from . $join . 
				SQLClient::WHERE . $where . SQLClient::GROUOPBY . $groupBy; 
		
		$result = DBClientHandler::getInstance ()->exec ($sql);
		$result = isset ($result) || $result!= NULL ? $result : NULL;
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
	
	/*public static function removeExercise($id, $course_id) {
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
	}*/
	
	
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