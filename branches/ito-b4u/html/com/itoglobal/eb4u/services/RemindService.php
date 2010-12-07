<?php

class RemindService {
	/**
	 * @var string defining the reminders table name
	 */
	const REMINDERS = 'reminders';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var string defining the category_id field name
	 */
	const CATEGORY_ID = 'category_id';
	/**
	 * @var string defining the subcategory_id field name
	 */
	const SUBCATEGORY_ID = 'subcategory_id';
	/**
	 * @var string defining the plan_id field name
	 */
	const PLAN_ID = 'plan_id';
	/**
	 * @var string defining the region_id field name
	 */
	const REGION_ID = 'region_id';
	
	private static function getReminds (){
		$fields = 't1.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::GETTER . SQLClient::FROM . '(' . 
					SQLClient::SELECT . self::MAILS . '.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::SENDER;
		$from = self::MAILS . SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . self::MAILS . '.' . 
				self::SENDER_ID . '=' . UsersService::USERS . '.' . UsersService::ID . ')' . SQLClient::SQL_AS . 't1' . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 't1.' . 
				self::GETTER_ID . '=' . UsersService::USERS . '.' . UsersService::ID;
		$orderby = self::CRDATE . ' ' . SQLClient::DESC;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '' , $orderby, '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function setRemind($user, $category, $subcategory, $plan, $region) {
		#Insert new users to DB
		$into = self::REMINDERS;
		$fields = self::USER_ID . ', ' . self::CATEGORY_ID . ', ' . self::SUBCATEGORY_ID . ', ' . 
				self::PLAN_ID . ', ' .	self::REGION_ID;
		$values = "'" . $user . "','" . $category . "','" . $subcategory . "','" . 
				$plan . "','" . $region . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		#get user id 
		return $result;
	}
	
	private static function updateRemind ($id, $fields, $vals){
		$from = self::REMINDERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteFromTrash($hash) {
		# setting the query variables
		$from = self::MAILS;
		$where = self::HASH . " = '" . $hash . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}	

}

?>