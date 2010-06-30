<?php

class ChallengesService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the name field name
	 */
	const NAME = 'name';
	/**
	 * @var string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var string defining the owner field name
	 */
	const OWNER = 'owner';
	/**
	 * @var  string defining the ex_index field name
	 */
	const EX_INDEX = 'ex_index';
	/**
	 * @var  string defining the challenges table name
	 */
	const CH_TABLE = 'challenges';
		
	public static function getChallengesList($where = NULL, $groupBy = NULL) {
		$fields = self::NAME . ", " . self::DESCRIPTION . ", " . self::OWNER . ", " . self::EX_INDEX;
		$from = self::CH_TABLE;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, '', '' );
		return $result;
	}
	
	public static function newChallenge($requestParams){
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		$fields = self::NAME . ', ' . self::DESCRIPTION . ', ' . self::OWNER . ', ' . self::EX_INDEX;
		$values = "'" . $requestParams[self::NAME] . "', '" . $requestParams[self::DESCRIPTION] . "', '" . 
					$user_id . "', '" . $requestParams[self::EX_INDEX] . "'";
		$into = self::CH_TABLE;
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	/**
	 * Retreives the challenge data by specified exercises id.
	 * @param integer $id the user id.
	 * @return mixed user data or null if user with such id does not exists. 
	 */
	public static function getChallenge($id) {
		$result = null;		
		if(isset($id) && $id != ''){
			# preparing query
			$fields = self::NAME . ", " . self::DESCRIPTION . ", " . self::OWNER . ", " . self::EX_INDEX;
			$from = self::CH_TABLE;
			$where = self::EX_INDEX . '=' . $id;
			# executing query
			$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
			$result = $result != null && isset($result) && count($result) > 0 ? $result : null;
		}
		return $result;
	}
	
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