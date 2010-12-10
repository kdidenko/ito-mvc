<?php

class PlanService {
	/**
	 * @var string defining the plan table name
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
	 * @var  string defining the tender_to field name
	 */
	const TENDER_TO = 'tender_to';
	/**
	 * @var  string defining the bargains field name
	 */
	const BARGAINS = 'bargains';
	/**
	 * @var  string defining the plan_desc field name
	 */
	const PLAN_DESC = 'plan_desc';
	
	
	const CRNT_PLAN = 'crnt_plan';
	
	const OTHER_PLAN = 'other_plan';
	/**
	 * Retrieves plan by specified plan id.
	 * @param integer $hash the plan id
	 * @return plan data
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
	
	public static function getPlans() {
		$fields = '*';
		$from = self::PLAN;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
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