<?php

class ValuateService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the training_id field name
	 */
	const RESP_ID = 'resp_index';
	/**
	 * @var string defining the training_name field name
	 */
	const COMMENT = 'comment';
	/**
	 * @var string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var string defining the course_id field name
	 */
	const VALUATE = 'valuate';
	/**
	 * @var  string defining the trainings table name
	 */
	const VALUATIONS_TBL = 'valuations';
		
	public static function getValuateList($where = NULL, $groupBy = NULL) {
		$fields = self::ID . "." . self::RESP_ID . ", " . self::COMMENT . ", " . self::VALUATE . ", " . 
					self::USER_ID;
		$from = self::VALUATIONS_TBL;
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
		/*
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
		*/
	}
	 
	public static function valuateResp($resp_id, $comment, $valuate){
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$fields = self::RESP_ID . ", " . self::COMMENT . ", " . self::USER_ID . ", " . self::VALUATE;
		$values = "'" . $resp_id . "', '" . $comment . "', '" . $user_id . "' , '" . $valuate . "'";
		$into = self::VALUATIONS_TBL;
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );					
	}
}

?>