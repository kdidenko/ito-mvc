<?php

class TrainingsService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the training_id field name
	 */
	const TRN_ID = 't_index';
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
		
	public static function getTrainingsList($where = NULL, $groupBy = NULL) {
		$fields = self::TRAININGS_TABLE . "." . self::ID . ", " . self::TRN_ID . ", " . self::TRN_NAME . ", " . 
					self::USER_ID . ", " . self::COURSE_ID . ", " . CourseService::CAPTION . ", " . CategoriesService::NAME;
		$from = self::TRAININGS_TABLE . SQLClient::LEFT . SQLClient::JOIN . CourseService::COURSE_TABLE . SQLClient::ON . 
					self::COURSE_ID . '=' . CourseService::COURSE_TABLE . '.' . CourseService::ID . 
					SQLClient::LEFT . SQLClient::JOIN . CategoriesService::CATEGORIES_TABLE . SQLClient::ON . 
					CourseService::CATEGORY_ID . '=' . CategoriesService::CATEGORIES_TABLE . '.' . CategoriesService::ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, '', '' );
		return $result;
	}
	
	/**
	 * Retreives the trainings data by specified id.
	 * @param integer $id the training id.
	 * @return mixed training data or null if training with such id does not exists. 
	 */
	public static function getTraining($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::TRAININGS_TABLE . "." . self::ID . ", " . self::TRN_ID . ", " . self::TRN_NAME . ", " . 
					self::USER_ID . ", " . self::COURSE_ID . ", " . CourseService::CAPTION;
			$from = self::TRAININGS_TABLE . SQLClient::LEFT . SQLClient::JOIN . CourseService::COURSE_TABLE . SQLClient::ON . 
					self::COURSE_ID . '=' . CourseService::COURSE_TABLE . '.' . CourseService::ID;
			$where = self::TRN_ID . '=' . $id;
			$order =  self::ID . ' ' .SQLClient::ASC;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', $order, '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result : null;
		} 
		return $result;
	}
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::TRAININGS_TABLE;
		$where = self::TRN_ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteTrainig($id, $where=NULL) {
		# setting the query variables
		$from = self::TRAININGS_TABLE;
		$where = $where==NULL ? self::TRN_ID . " = '" . $id . "'" : $where;
		# executing the query
		DBClientHandler::getInstance ()->execDelete ($from, $where, NULL, NULL);
	}
	
	public static function addTraining($requestParams, $t_index, $course_id, $user_t_index){
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		# Insert new school to DB
		$fields = TrainingsService::TRN_ID . ", " . TrainingsService::TRN_NAME . ", " . TrainingsService::USER_ID . ", " . TrainingsService::COURSE_ID;
		$t_name = $requestParams [TrainingsService::TRN_NAME] == NULL ? 'Training ' . $user_t_index : $requestParams [TrainingsService::TRN_NAME];
		$values = "'" . $t_index . "', '" . $t_name . "', '" . $user_id . "' , '" . $course_id . "'";
		$into = TrainingsService::TRAININGS_TABLE;
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );					
	}

}

?>