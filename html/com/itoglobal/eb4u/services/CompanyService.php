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
	 * @var  string defining the order_id field name
	 */
	const ORDER_ID = 'order_id';
	/**
	 * @var  string defining the vote field name
	 */
	const VOTE = 'vote';
	/**
	 * @var  string defining the vote1 field name
	 */
	const VOTE1 = 'vote1';
	/**
	 * @var  string defining the vote2 field name
	 */
	const VOTE2 = 'vote2';
	/**
	 * @var  string defining the vote3 field name
	 */
	const VOTE3 = 'vote3';
	/**
	 * @var  string defining the vote4 field name
	 */
	const VOTE4 = 'vote4';
	/**
	 * @var  string defining the vote5 field name
	 */
	const VOTE5 = 'vote5';
	/**
	 * @var  string defining the vote6 field name
	 */
	const VOTE6 = 'vote6';
	/**
	 * @var  string defining the comment field name
	 */
	const COMMENT = 'comment';
	/**
	 * @var  string defining the date field name
	 */
	const DATE = 'date';
	/**
	 * @var  string defining the done field name
	 */
	const DONE = 'done';
	
	/**
	 * @var  string defining the company_projects table name
	 */
	const COMPANY_PROJECT = 'company_projects';
	/**
	 * @var  string defining the project_url field name
	 */
	const PROJECT_URL = 'project_url';
	/**
	 * @var  string defining the project_title field name
	 */
	const PROJECT_TITLE = 'project_title';
	
	/**
	 * @var  string defining the company_certificates table name
	 */
	const COMPANY_CARTIFICATES = 'company_certificates';
	/**
	 * @var  string defining the upload_id table name
	 */
	const UPLOAD_ID = 'upload_id';
	/**
	 * @var  string defining the certificate_titile field name
	 */
	const CARTIFICATES_TITLE = 'certificate_titile';
	
	/**
	 * @var  string defining the company_references table name
	 */
	const COMPANY_REFERENCES = 'company_references';
	/**
	 * @var  string defining the reference_title field name
	 */
	const REFERENCE_TITLE = 'reference_title';
	
	public static function getFeedback($where,$limit=NULL) {
		$fields = self::COMPANY_FEEDBACK . '.*' . ', ' . UsersService::USERNAME . ', ' . 
				OrdersService::ORDERS . '.' . OrdersService::ORDER_NAME . ', ' .
				OrdersService::ORDERS . '.' . OrdersService::HASH . ', ' .
					UsersService::AVATAR;
		$from = self::COMPANY_FEEDBACK . 
				SQLClient::LEFT . SQLClient::JOIN . OrdersService::ORDERS .	SQLClient::ON . 
				OrdersService::ORDERS . '.' . OrdersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::ORDER_ID . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::USER_ID;
		# executing the query
		$groupBy = self::COMPANY_FEEDBACK . '.' . self::ID;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy , '', $limit );
		if($result != null && isset($result) && count($result) > 0){
			foreach($result as $key => $value){
				$result[$key][self::VOTE] = round($value[self::VOTE]*20);
				$result[$key][self::VOTE1] = round($value[self::VOTE1]*20);
				$result[$key][self::VOTE2] = round($value[self::VOTE2]*20);
				$result[$key][self::VOTE3] = round($value[self::VOTE3]*20);
				$result[$key][self::VOTE4] = round($value[self::VOTE4]*20);
				$result[$key][self::VOTE5] = round($value[self::VOTE5]*20);
				$result[$key][self::VOTE6] = round($value[self::VOTE6]*20);
			}
		}else{
			$result = false;
		}
		return $result;
	}
	
	public static function getRecommendations($where) {
		$fields = self::COMPANY_FEEDBACK . '.*' . ', ' . UsersService::USERNAME . ', ' . 
					CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . ', ' .
					OrdersService::ORDERS . '.' . OrdersService::ORDER_NAME . ', ' .
					OrdersService::BOUGHT_ORDERS . '.' . OrdersService::BOUGHT_DATE . ', ' .
					UsersService::AVATAR;
		$from = self::COMPANY_FEEDBACK . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::COMPANY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . OrdersService::ORDERS .	SQLClient::ON . 
				OrdersService::ORDERS . '.' . OrdersService::ID . '=' . 
				self::COMPANY_FEEDBACK . '.' . self::ORDER_ID .
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY .	SQLClient::ON . 
				CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				OrdersService::ORDERS . '.' . OrdersService::CATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . OrdersService::BOUGHT_ORDERS .	SQLClient::ON . 
				self::COMPANY_FEEDBACK . '.' . self::ORDER_ID . '=' . 
				OrdersService::BOUGHT_ORDERS . '.' . OrdersService::ORDER_ID;
		# executing the query
		$groupBy = self::COMPANY_FEEDBACK . '.' . self::ID;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function countFeedback ($where = NULL){
		$fields =  SQLClient::COUNT . "(" . self::ID . ") as " . self::COMPANY_FEEDBACK;
		$from = self::COMPANY_FEEDBACK;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	public static function getProjects($company_id, $where=null) {
		$fields = self::COMPANY_PROJECT . '.*';
		$from = self::COMPANY_PROJECT;
		# executing the query
		$where .= $where!=NULL ? " AND " : NULL;
		$where .= self::COMPANY_ID . '=' . $company_id;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function setPoject($company_id, $project_url, $project_title) {
		$into = self::COMPANY_PROJECT;
		$fields = self::COMPANY_ID . ', ' . self::PROJECT_URL . ', ' . self::PROJECT_TITLE; 
		$values = "'" . $company_id . "', '" . $project_url . "', '" . $project_title . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into);
		return $result;
	}
	
	public static function delPoject($company_id, $project_id) {
		# setting the query variables
		$from = self::COMPANY_PROJECT;
		$where = self::ID . " = " . $project_id . " AND " . self::COMPANY_ID . '=' . $company_id;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
	}
	
	public static function getCertificates($company_id, $where=null) {
		$fields = self::COMPANY_CARTIFICATES . '.*' . ', ' . UploadsService::PATH;
		$from = self::COMPANY_CARTIFICATES . 
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS .	SQLClient::ON . 
				UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				self::COMPANY_CARTIFICATES . '.' . self::UPLOAD_ID;
		# executing the query
		$where .= $where!=NULL ? " AND " : NULL;
		$where .= self::COMPANY_ID . '=' . $company_id;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}

	public static function setCertificates($company_id, $upload_id, $cartificates_title) {
		$into = self::COMPANY_CARTIFICATES;
		$fields = self::COMPANY_ID . ', ' . self::UPLOAD_ID . ', ' . self::CARTIFICATES_TITLE; 
		$values = "'" . $company_id . "', '" . $upload_id . "', '" . $cartificates_title . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into);
		return $result;
	}
	
	public static function delCertificate($company_id, $project_id, $upload_id, $path) {
		# delete from certificates table
		$from = self::COMPANY_CARTIFICATES;
		$where = self::ID . " = " . $project_id . " AND " . self::COMPANY_ID . '=' . $company_id;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
		#delete from upload table
		UploadsService::delUploads($upload_id, path);
	}
	
	public static function getReferences($company_id, $where=null) {
		$fields = self::COMPANY_REFERENCES . '.*' . ', ' . UploadsService::PATH;
		$from = self::COMPANY_REFERENCES . 
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS .	SQLClient::ON . 
				UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				self::COMPANY_REFERENCES . '.' . self::UPLOAD_ID;
		# executing the query
		$where .= $where!=NULL ? " AND " : NULL;
		$where .= self::COMPANY_ID . '=' . $company_id;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function delReference($company_id, $project_id, $upload_id, $path) {
		# delete from certificates table
		$from = self::COMPANY_REFERENCES;
		$where = self::ID . " = " . $project_id . " AND " . self::COMPANY_ID . '=' . $company_id;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
		#delete from upload table
		UploadsService::delUploads($upload_id, $path);
	}
	
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
	 	$where .= ' AND ' . self::DONE . '=5';
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function postReview ($id, $user_id, $fields, $vals){
		$from = self::COMPANY_FEEDBACK;
		$where = self::ID . " = '" . $id . "' AND " . self::USER_ID . " = '" . $user_id . "'";;
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function feedbackCompany($user_id, $company_id, $order_id) {
		$into = self::COMPANY_FEEDBACK;
		$fields = self::USER_ID . ', ' . self::COMPANY_ID . ', ' . self::ORDER_ID; 
		$values = "'" . $user_id . "', '" . $company_id . "', '" . $order_id . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into);
		return $result;
	}
}

?>