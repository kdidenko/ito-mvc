<?php

class ResponsesService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the name field name
	 */
	const NAME = 'name';
	/**
	 * @var string defining the owner field name
	 */
	const OWNER = 'owner';
	/**
	 * @var  string defining the ex_index field name
	 */
	const EX_INDEX = 'ex_index';
	/**
	 * @var  string defining the chlg_index field name
	 */
	const CHLG_INDEX = 'chlg_index';
	/**
	 * @var string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var string defining the crdate field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the responses table name
	 */
	const RESP_TABLE = 'responses';
	
	const CHLG_DESC = 'chlg_disc';
	const CHLG_NAME = 'chlg_name';
	const EX_DESC = 'ex_disc';
	const EX_NAME = 'ex_name';
	const EX_ID = 'ex_id';
	
	/**
	 * Retreives the responses data by specified user id or valuation id.
	 * @param integer $owner the user id.
	 * @param integer $v_index the valuation index.
	 * @param integer $limit the limit of responses.
	 * @return mixed responses data or null if responses with such user id does not exists. 
	 */	
	public static function getResponses($owner=NULL, $limit = null, $v_index = NULL) {
		/* SQL QUERY:
		 * SELECT r.id, r.name, r.description AS r_descr, c.id AS ch_id, c.caption AS ch_name, c.description AS ch_descr, e.id AS e_id, e.caption AS e_name, e.description AS e_descr
		 * FROM `responses` AS r
		 * LEFT JOIN challenges AS c ON c.id=r.chlg_index
		 * LEFT JOIN exercises AS e ON e.id=c.ex_index
		 * [LEFT JOIN valuations AS v ON v.course_id=e.course_id
		 * WHERE v.v_index=2]
		 * WHERE r.owner=37
		 */
		$user_id = SessionService::getAttribute ( SessionService::USERS_ID );
		$fields = self::RESP_TABLE . '.' . self::ID . ', ' . 
				self::RESP_TABLE . '.' . self::DESCRIPTION . ', ' .
				self::RESP_TABLE . '.' . self::CRDATE . ', ' .
				//ChallengesService::CH_TABLE . '.' . ChallengesService::ID . ', ' . 
				ChallengesService::CH_TABLE . '.' . ChallengesService::CAPTION . SQLClient::SQL_AS . self::CHLG_NAME . ', ' . 
				ChallengesService::CH_TABLE . '.' . ChallengesService::DESCRIPTION . SQLClient::SQL_AS . self::CHLG_DESC . ', ' . 
				ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::ID . SQLClient::SQL_AS . self::EX_ID . ', ' .
				ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::CAPTION . SQLClient::SQL_AS . self::EX_NAME . ', ' .
				ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::DESCRIPTION . SQLClient::SQL_AS . self::EX_DESC; 
		$from = self::RESP_TABLE;
		$join = SQLClient::LEFT . SQLClient::JOIN . ChallengesService::CH_TABLE . SQLClient::ON . 
				ChallengesService::CH_TABLE . '.' . ChallengesService::ID . '=' . 
				self::RESP_TABLE . '.' . self::CHLG_INDEX . 
				SQLClient::LEFT . SQLClient::JOIN . ExerciseService::EXERCISES_TABLE . SQLClient::ON . 
				ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::ID . '=' . 
				ChallengesService::CH_TABLE . '.' . ChallengesService::EX_INDEX;
		$join .= isset($v_index) ? 
					SQLClient::LEFT . SQLClient::JOIN . ValuationsService::V_TABLE . SQLClient::ON . 
					ValuationsService::V_TABLE . '.' . ValuationsService::COURSE_ID . '=' . 
					ExerciseService::EXERCISES_TABLE . '.' . ExerciseService::COURSE_ID : 
						NULL; 
		$where = isset($owner) ? SQLClient::WHERE . self::RESP_TABLE . '.' . self::OWNER . '=' . $owner : NULL;
		$where = isset($v_index) ? 
					SQLClient::WHERE . ValuationsService::V_TABLE . '.' . ValuationsService::V_ID . '=' . $v_index . 
					' AND ' .  ValuationsService::V_TABLE . '.' . ValuationsService::USER_ID . '!=' . $user_id
						: $where;
		# executing the query
		$sql = SQLClient::SELECT . $fields . SQLClient::FROM . $from . $join . 
				$where . SQLClient::LIMIT . $limit;  
		$result = DBClientHandler::getInstance ()->exec ( $sql );
		$result = count($result)>0 && $result!=NULL ? $result : NULL;
		return $result;
	}
	
	public static function newResponse($requestParams){
		$user_id = SessionService::getAttribute(SessionService::USERS_ID);
		$crdate = gmdate ( "Y-m-d H:i:s" );
		$fields = self::NAME . ', ' . self::CHLG_INDEX . ', ' . self::OWNER . ', ' . self::EX_INDEX . ', ' . 
					self::DESCRIPTION . ', ' . self::CRDATE;
		$values = "'" . $requestParams[self::NAME] . "', '" . $requestParams[self::CHLG_INDEX] . "', '" .
					$user_id . "', '" . $requestParams[self::EX_INDEX] . "', '" . 
					$requestParams[self::DESCRIPTION] . "', '" . $crdate . "'";
		$into = self::RESP_TABLE;
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
}

?>