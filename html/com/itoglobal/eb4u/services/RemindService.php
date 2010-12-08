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
	
	public static function getRemindsByUser ($user){
		$fields = self::REMINDERS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME;
		$from = self::REMINDERS . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::REMINDERS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::REMINDERS . '.' . self::SUBCATEGORY_ID;
		$where = self::USER_ID . '=' . $user;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
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
	
	public static function updateRemind ($id, $fields, $vals){
		$from = self::REMINDERS;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteByUser($user) {
		# setting the query variables
		$from = self::REMINDERS;
		$where = self::USER_ID . " = '" . $user . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
	}	

}

?>