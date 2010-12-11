<?php

class BargainsService {
	/**
	 * @var string defining the bargains table name
	 */
	const BARGAINS = 'bargains';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var  string defining the bargain_name field name
	 */
	const BARGAIN_NAME = 'bargain_name';
	/**
	 * @var string defining the bargain_desc field name
	 */
	const BARGAIN_DESC = 'bargain_desc';
	/**
	 * @var string defining the category_id field name
	 */
	const CATEGORY_ID = 'category_id';
	/**
	 * @var string defining the subcategory_id field name
	 */
	const SUBCATEGORY_ID = 'subcategory_id';
	/**
	 * @var string defining the usual_price field name
	 */
	const USUAL_PRICE = 'usual_price';
	/**
	 * @var string defining the bargain_price field name
	 */
	const BARGAIN_PRICE = 'bargain_price';
	/**
	 * @var string defining the bargain_image field name
	 */
	const BARGAIN_IMAGE = 'bargain_image';
	/**
	 * @var string defining the street field name
	 */
	const STREET= 'street';
	/**
	 * @var string defining the zip field name
	 */
	const ZIP = 'zip';
	/**
	 * @var string defining the city field name
	 */
	const CITY = 'city';
	/**
	 * @var string defining the region field name
	 */
	const REGION = 'region';
	/**
	 * @var string defining the country field name
	 */
	const COUNTRY = 'country';
	/**
	 * @var string defining the website field name
	 */
	const WEBSITE = 'website';
	/**
	 * @var string defining the from_date field name
	 */
	const FROM_DATE = 'from_date';
	/**
	 * @var string defining the until_date field name
	 */
	const UNTIL_DATE = 'until_date';
	/**
	 * @var string defining the number field name
	 */
	const NUMBER = 'number';
	/**
	 * @var string defining the status field name
	 */
	const STATUS = 'status';
	
	/**
	 * Retrieves the users bargains by specified user id.
	 * @param integer $user the user id.
	 * @return mixed bargains data or null if user with such id does not exists. 
	 */
	public static function getBargains ($user){
		$fields = self::BARGAINS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME;
		$from = self::BARGAINS . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::SUBCATEGORY_ID;
		$where = self::USER_ID . '=' . $user;
		$orderby = self::ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , $orderby, '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	
	/*
	public static function updateMail ($hash, $fields, $vals){
		$from = self::MAILS;
		$where = self::HASH . " = '" . $hash . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	*/
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $hash the mail id
	 * @return mail data
	 */
	/*
	public static function getMail($hash) {
		$fields = 't1.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::GETTER . SQLClient::FROM . '(' . 
					SQLClient::SELECT . self::MAILS . '.*,' . UsersService::USERNAME . SQLClient::SQL_AS . self::SENDER;
		$from = self::MAILS . SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . self::MAILS . '.' . 
				self::SENDER_ID . '=' . UsersService::USERS . '.' . UsersService::ID . ')' . SQLClient::SQL_AS . 't1' . 
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 't1.' . 
				self::GETTER_ID . '=' . UsersService::USERS . '.' . UsersService::ID;
		$where = self::HASH . "='" . $hash . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? 
					self::createDate($result, true, true) : 
						false;
		return $result[0];
	}
	*/
	/**
	 * function for sending mail
	 * @param string $subject subject of letter
	 * @param string $text text of letter
	 * @param integer $from sender
	 * @param integer $to getter
	 * @return mail id
	 */
	/*
	public static function sendMail($subject, $text, $from, $to){
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $from);
		$inbox = '3';
		$outbox = '4';
		#Insert new users to DB
		$into = self::MAILS;
		$fields = self::SUBJECT . ', ' . self::TEXT . ', ' . self::SENDER_ID . ', ' . self::GETTER_ID . ', ' . 
					self::CRDATE . ', ' . self::HASH . ', ' . self::STATUS;
		$values = "'" . $subject . "','" . $text . "','" . $from . "','" . $to . "','" . $date . "','" . 
					$hash ."','" . $outbox . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $from . $to);
		$fields = self::SUBJECT . ', ' . self::TEXT . ', ' . self::SENDER_ID . ', ' . self::GETTER_ID . ', ' . 
					self::CRDATE . ', ' . self::HASH . ', ' . self::STATUS;
		$values = "'" . $subject . "','" . $text . "','" . $from . "','" . $to . "','" . $date . "','" . 
					$hash ."','" . $inbox . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		#get user id 
		$id = $result;
		return $result;
	}
	*/	
	/**
	 * delete mail from trash
	 * @param integer $id the mail id
	 */
	/*
	public static function deleteFromTrash($hash) {
		# setting the query variables
		$from = self::MAILS;
		$where = self::HASH . " = '" . $hash . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}
	*/	

}

?>