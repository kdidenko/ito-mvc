<?php

class CompanyService {
	/**
	 * @var string defining the company_feedback table name
	 */
	const COMPANY_FEEDBACK = 'company_feedback';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var  string defining the company_id field name
	 */
	const COMPANY_ID = 'company_id';
	/**
	 * @var  string defining the vote field name
	 */
	const VOTE = 'vote';
	/**
	 * @var  string defining the comment field name
	 */
	const COMMENT = 'comment';
	/**
	 * @var  string defining the date field name
	 */
	const DATE = 'date';

	public static function getFeedback($company_id) {
		$fields = self::COMPANY_FEEDBACK . '.*' . ', ' . UsersService::USERNAME . ', ' . 
					UsersService::AVATAR . 
					", SUM( company_feedback.vote ) / COUNT( company_feedback.id )*20 AS count";
		$from = self::COMPANY_FEEDBACK . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::USER_ID;
		# executing the query
		$where = self::COMPANY_ID . '=' . $company_id;
		$groupBy = self::COMPANY_FEEDBACK . '.' . self::ID;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/*
	 
	 SELECT SUM( vote ) / COUNT( id ) AS count
	FROM  `company_feedback`
	 
	 SELECT users.*, SUM( company_feedback.vote ) / COUNT( company_feedback.id ) AS vote,
COUNT( company_feedback.vote ) AS count
FROM users 
LEFT JOIN company_feedback
ON company_feedback.company_id=users.id
WHERE role='TR'
GROUP BY users.id
	 */
	
	public static function getRating($company_id = NULL, $where = NULL) {
		$fields = self::COMPANY_FEEDBACK . '.*' . ', ' . UsersService::USERNAME . ', ' . 
					UsersService::AVATAR;
		$from = self::COMPANY_FEEDBACK . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::USER_ID;
		# executing the query
		$where .= $where!=NULL&&$company_id!=NULL ? " AND " : NULL;
		$where .= $company_id!=NULL ? self::COMPANY_ID . '=' . $company_id : NULL;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function feedbackCompany($user_id, $company_id, $vote, $comment) {
		$into = self::COMPANY_FEEDBACK;
		//$comment = htmlspecialchars($comment, ENT_QUOTES);
		$fields = self::USER_ID . ', ' . self::COMPANY_ID . ', ' . 
				elf::VOTE . ', ' . self::COMMENT; 
		$values = "'" . $user_id . "', '" . $company_id . "', '" . $vote . "', '" . $comment . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into);
		return $result;
	}
	
}

?>