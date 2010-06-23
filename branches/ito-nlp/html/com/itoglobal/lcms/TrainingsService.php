<?php

class TrainingsService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the training_id field name
	 */
	const TRN_ID = 'training_id';
	/**
	 * @var string defining the training_name field name
	 */
	const TRN_NAME = 'training_name';
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
	const TRAININGS_TABLE = 'trainings';
		
	public static function getTrainingList($where = NULL) {
		$fields = self::TRAININGS_TABLE . "." . self::ID . ", " . self::TRN_ID . ", " . self::TRN_NAME . ", " . self::USER_ID . ", " . self::COURSE_ID;
		$from = self::TRAININGS_TABLE;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		return $result;
	}
	
	/**
	 * Retreives the user data by specified user id.
	 * @param integer $id the user id.
	 * @return mixed user data or null if user with such id does not exists. 
	 */
	/*public static function getUser($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::ID . ', ' . self::USERNAME . ', ' . SessionService::FIRSTNAME . ', ' . 
						SessionService::LASTNAME . ', ' . SessionService::EMAIL . ', ' . 
						self::ENABLED . ', ' . self::DELETED . ', ' . self::ROLE . ', ' . self::AVATAR;
			$from = self::USERS;
			$where = self::ID . '=' . $id;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : null;
		} 
		return $result;
	}*/
	
	public static function updateFields(/*$id, $fields, $vals*/) {
		/*# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );*/
	}
	
	public static function deleteTrainig(/*$id, $fields, $vals*/) {
		/*# setting the query variables
		$from = self::USERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );*/
	}

}

?>