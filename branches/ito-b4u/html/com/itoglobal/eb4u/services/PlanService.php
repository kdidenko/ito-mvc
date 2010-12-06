<?php

class PlanService {
	/**
	 * @var string defining the mails table name
	 */
	const PLAN = 'plan';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the plan_name field name
	 */
	const PLAN_NAME = 'plan_name';
	/**
	 * @var  string defining the plan_price field name
	 */
	const PLAN_PRICE = 'plan_price';
	
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $hash the mail id
	 * @return mail data
	 */
	public static function getPlan($id) {
		$fields = '*';
		$from = self::PLAN;
		$where = self::ID . '=' . $id;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	public static function getOtherPlans($id) {
		$fields = '*';
		$from = self::PLAN;
		$where = self::ID . '>' . $id;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/*
	public static function updatePlan ($id, $fields, $vals){
		$from = self::PLAN;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	*/
	/**
	 * delete mail from trash
	 * @param integer $id the mail id
	 
	public static function deleteCategory($id) {
		# setting the query variables
		$from = self::CATEGORY;
		$where = self::ID . " = '" . $id . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}	
*/
}

?>