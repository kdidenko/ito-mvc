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
	 * @var string defining the crdate field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the count field name
	 */
	const COUNT = 'count';
	/**
	 * @var  string defining the trainings table name
	 */
	const VALUATIONS_TBL = 'valuations';
		
	public static function getValuateList($resp_index, $user_id = NULL, $limit = NULL) {
		$fields = self::VALUATIONS_TBL . '.' . self::ID . "," . self::RESP_ID . ", " . self::COMMENT . ", " . 
				self::VALUATE . ", " . self::USER_ID . ", " . self::VALUATIONS_TBL . '.' . self::CRDATE . ", " . 
				UsersService::USERS . '.' . UsersService::FIRSTNAME . ", " .
				UsersService::USERS . '.' . UsersService::LASTNAME . ", " . 
				UsersService::USERS . '.' . UsersService::AVATAR;
		$from = self::VALUATIONS_TBL . SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
				self::VALUATIONS_TBL . '.' . self::USER_ID . '=' . UsersService::USERS . '.' . UsersService::ID ;
		$where = isset($resp_index) ? self::RESP_ID . '=' . $resp_index : NULL;
		$where .= isset($user_id) ? ' AND ' . self::USER_ID . '=' . $user_id : NULL;
		$orderBy = self::ID . ' ' . SQLClient::DESC;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, NULL, $orderBy, $limit );
		$result = isset($result) && count($result)>0 ? $result : NULL;
		return $result;
	}
	 
	public static function valuateResp($resp_id, $comment, $valuate){
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$crdate = gmdate ( "Y-m-d H:i:s" );
		$fields = self::RESP_ID . ", " . self::COMMENT . ", " . self::USER_ID . ", " . self::VALUATE . ", " . self::CRDATE;
		$values = "'" . $resp_id . "', '" . $comment . "', '" . $user_id . "' , '" . $valuate . "' , '" . $crdate . "'";
		$into = self::VALUATIONS_TBL;
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );					
	}
	
	public static function countVotes($resp_id){
		/*
		 * SELECT valuate, count(valuate)
		 * FROM `valuations` 
		 * WHERE `resp_index`=1
		 * GROUP BY valuate
		 * ORDER BY valuate DESC
		 */
		$fields = self::VALUATE . ", " . SQLClient::COUNT . '(' . self::VALUATE . ')' . SQLClient::SQL_AS . self::COUNT; 
		$from = self::VALUATIONS_TBL;
		$where = isset($resp_id) ? self::RESP_ID . '=' . $resp_id : NULL;
		$groupBy = self::VALUATE;
		$groupBy = self::VALUATE . ' ' . SQLClient::DESC;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, NULL, NULL );
		$result = isset($result) && count($result)>0 ? $result : NULL;
		return $result;
	}
	
	public static function countPoints($votes, $sum){
		#count skill points
		$points = NULL;
		foreach ($votes as $key =>$value){
			$points = $points + ($value[ValuateService::COUNT]*$value[ValuateService::VALUATE]);
		}
		$points = $points/$sum;
		$points = round($points, 2);
		return $points;
	}
	public static function countNumberVotes($votes){
		#count number of votes
		$sum = NULL;
		foreach ($votes as $key =>$value){
			$sum = $sum + $value[ValuateService::COUNT];
		}
		return $sum;
	}
}

?>