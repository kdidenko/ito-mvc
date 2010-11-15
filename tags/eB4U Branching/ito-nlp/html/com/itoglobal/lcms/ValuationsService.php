<?php

class ValuationsService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the training_id field name
	 */
	const V_ID = 'v_index';
	/**
	 * @var string defining the training_name field name
	 */
	const V_NAME = 'v_name';
	/**
	 * @var string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var string defining the course_id field name
	 */
	const COURSE_ID = 'course_id';
	/**
	 * @var  string defining the trainings table name
	 */
	const V_TABLE = 'valuation_assignments';
		
	public static function getValuationsList($where = NULL, $groupBy = NULL) {
		$fields = self::V_TABLE . "." . self::ID . ", " . self::V_ID . ", " . self::V_NAME . ", " . 
					self::USER_ID . ", " . self::COURSE_ID;
		$from = self::V_TABLE;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, '', '' );
		return $result;
	}
	
	/**
	 * Retreives the user data by specified user id.
	 * @param integer $id the user id.
	 * @return mixed user data or null if user with such id does not exists. 
	 */
	public static function getValuation($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::V_TABLE . "." . self::ID . ", " . self::V_ID . ", " . self::V_NAME . ", " . 
					self::USER_ID . ", " . self::COURSE_ID . ", " . self::COURSE_ID . ", " . CourseService::CAPTION;
			$from = self::V_TABLE . SQLClient::LEFT . SQLClient::JOIN . CourseService::COURSE_TABLE . SQLClient::ON . 
					self::COURSE_ID . '=' . CourseService::COURSE_TABLE . '.' . CourseService::ID;
			$where = self::V_ID . '=' . $id;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result : null;
		} 
		return $result;
	}
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::V_TABLE;
		$where = self::V_ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteValuation($id, $where=NULL) {
		# setting the query variables
		$from = self::V_TABLE;
		$where = $where==NULL ? self::V_ID . " = '" . $id . "'" : $where;
		# executing the query
		DBClientHandler::getInstance ()->execDelete ($from, $where, NULL, NULL);
	}
	
	public static function addValuations($requestParams, $v_index, $course_id, $user_v_index){
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		# Insert new school to DB
		$fields = ValuationsService::V_ID . ", " . ValuationsService::V_NAME . ", " . ValuationsService::USER_ID . ", " . ValuationsService::COURSE_ID;
		$v_name = $requestParams [ValuationsService::V_NAME] == NULL ? 'Valuation ' . $user_v_index : $requestParams [ValuationsService::V_NAME];
		$values = "'" . $v_index . "', '" . $v_name . "', '" . $user_id . "' , '" . $course_id . "'";
		$into = ValuationsService::V_TABLE;
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );					
	}
}

?>