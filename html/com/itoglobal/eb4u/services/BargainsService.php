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
	 * @var string defining the time_left field name
	 */
	const TIME_LEFT = 'time_left';
	/**
	 * @var string defining the number field name
	 */
	const NUMBER = 'number';
	/**
	 * @var string defining the status field name
	 */
	const STATUS = 'status';
	/**
	 * @var string defining the status field name
	 */
	const HASH = 'hash';
	/**
	 * @var string defining the discount field name
	 */
	const DISCOUNT = 'discount';
	
	const FROM_PRICE = "price_from";
	const UNTIL_PRICE = "price_until";
	
	const BARGAIN_RELATIONS = 'bargain_relations';
 	const BARGAIN_ID = 'bargain_id';
 	const UPLOAD_ID = 'upload_id';
 	
 	const BOUGHT_BARGAIN = 'bought_bargain';
 	const BOUGHT_DATE = 'bought_date';
	
 	public static function setBargain($id, $data, $path){
		$into = self::BARGAINS;
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $id);
		$discount = $data[self::USUAL_PRICE] - $data[self::BARGAIN_PRICE];
		$discount = round($discount / ($data[self::USUAL_PRICE]/100));
		$fields = self::USER_ID . ', ' . self::BARGAIN_NAME . ', ' . self::BARGAIN_DESC . ', ' . 
					self::CATEGORY_ID . ', ' . self::SUBCATEGORY_ID . ', ' . self::USUAL_PRICE . ', ' . 
					self::BARGAIN_PRICE . ', ' . self::DISCOUNT . ', ' . self::STREET . ', ' . 
					self::ZIP . ', ' . self::CITY . ', ' . self::REGION . ', ' . 
					self::COUNTRY . ', ' . self::WEBSITE . ', ' . self::FROM_DATE . ', ' . 
					self::UNTIL_DATE . ', ' . self::NUMBER . ', ' . self::STATUS . ', ' . self::HASH;
		$values = "'" . $id . "','" . $data[self::BARGAIN_NAME] . "','" . 
					$data[self::BARGAIN_DESC] . "','" . $data[self::CATEGORY_ID] . "','" . 
					$data[self::SUBCATEGORY_ID] . "','" . $data[self::USUAL_PRICE] ."','" .
					$data[self::BARGAIN_PRICE] . "','" . $discount . "','" . 
					$data[self::STREET] . "','" . $data[self::ZIP] ."','" .
					$data[self::CITY] . "','" . $data[self::REGION] . "','" . 
					$data[self::COUNTRY] . "','" . $data[self::WEBSITE] ."','" .
					$data[self::FROM_DATE] . "','" . $data[self::UNTIL_DATE] ."','" . 
					$data[self::NUMBER] . "','" . '1' . "','" . $hash . "'";
					
		$bargain_id = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		$upload_id = UploadsService::setUploadsPath($path);
		$into = self::BARGAIN_RELATIONS;
		$fields = self::BARGAIN_ID . ',' . self::UPLOAD_ID;
		$values = "'" . $bargain_id . "', '" . $upload_id . "'";
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function getBargainImgs ($order_id){
		$fields = self::BARGAIN_RELATIONS . '.*, ' . UploadsService::UPLOADS . '.' . UploadsService::PATH;
		$from = self::BARGAIN_RELATIONS . 
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS . 
				SQLClient::ON . UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				self::BARGAIN_RELATIONS . '.' . self::UPLOAD_ID;
		$where = self::BARGAIN_ID . "='" . $order_id . "'"; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/**
	 * delete bargain from db
	 * @param integer $id the mail id
	 */
	public static function deleteBargains($string){
		if (isset($string) && count($string)>0){
			$array = explode(',', $string);
			foreach ($array as $bargain){
				# setting the query variables
				$from = self::BARGAINS;
				$where = self::HASH . " = '" . $bargain . "'";
				$orderBy = null;
				$limit = null;
				# executing the query
				DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
			}
		}
	}
	
	/**
	 * Retrieves the users bargains by specified user id.
	 * @param integer $user the user id.
	 * @return mixed bargains data or null if user with such id does not exists. 
	 */
	public static function getBargains ($where = NULL){
		$fields = self::BARGAINS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME . 
				', ' . RegionService::REGIONS . '.' . RegionService::REGION_NAME . 
				', ' . CountryService::COUNTRY . '.' . CountryService::COUNTRY_NAME . 
				', ' . UsersService::USERS . '.' . UsersService::USERNAME .
				', ' . UploadsService::UPLOADS . '.' . UploadsService::PATH;
		$from = self::BARGAINS . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::SUBCATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . RegionService::REGIONS .	SQLClient::ON . 
				RegionService::REGIONS . '.' . RegionService::ID . '=' . 
				self::BARGAINS . '.' . self::REGION . 
				SQLClient::LEFT . SQLClient::JOIN . CountryService::COUNTRY .	SQLClient::ON . 
				CountryService::COUNTRY . '.' . CountryService::ID . '=' . 
				self::BARGAINS . '.' . self::COUNTRY .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::BARGAINS . '.' . self::USER_ID .
				SQLClient::LEFT . SQLClient::JOIN . self::BARGAIN_RELATIONS . SQLClient::ON . 
				self::BARGAIN_RELATIONS . '.' . self::BARGAIN_ID . '=' . 
				self::BARGAINS . '.' . self::ID .
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS . SQLClient::ON . 
				UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				self::BARGAIN_RELATIONS . '.' . self::UPLOAD_ID;
		$orderby = self::ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , $orderby, '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	
	public static function updateFields ($hash, $fields, $vals){
		$from = self::BARGAINS;
		$where = self::HASH . " = '" . $hash . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function countBargains ($where = NULL){
		$fields =  SQLClient::COUNT . "(" . self::ID . ") as " . self::BARGAINS;
		$from = self::BARGAINS;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	public static function getBoughtBargain ($where){
		$fields = self::BOUGHT_BARGAIN . '.*, ' . self::BARGAINS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME . 
				', ' . RegionService::REGIONS . '.' . RegionService::REGION_NAME . 
				', ' . CountryService::COUNTRY . '.' . CountryService::COUNTRY_NAME . 
				', ' . UsersService::USERS . '.' . UsersService::USERNAME; 
		$from = self::BOUGHT_BARGAIN . 
				SQLClient::LEFT . SQLClient::JOIN . self::BARGAINS . 
				SQLClient::ON . self::BOUGHT_BARGAIN . '.' . self::BARGAIN_ID . '=' . 
				self::BARGAINS . '.' . self::ID . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::BARGAINS . '.' . self::SUBCATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . RegionService::REGIONS .	SQLClient::ON . 
				RegionService::REGIONS . '.' . RegionService::ID . '=' . 
				self::BARGAINS . '.' . self::REGION . 
				SQLClient::LEFT . SQLClient::JOIN . CountryService::COUNTRY .	SQLClient::ON . 
				CountryService::COUNTRY . '.' . CountryService::ID . '=' . 
				self::BARGAINS . '.' . self::COUNTRY .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::BARGAINS . '.' . self::USER_ID;
		$orderby = self::BOUGHT_DATE;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , $orderby, '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
 	public static function buyBargain($bargain_id, $user_id){
		// update number of availeble bargains
		$from = self::BARGAINS;
		$fields = self::NUMBER;
		$vals = self::NUMBER . '-1'; 
		$where = self::ID . " = '" . $bargain_id . "'";
		# executing the query
		$id = DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
		// insert new row in table
 		$into = self::BOUGHT_BARGAIN;
		$date = gmdate ( "Y-m-d H:i:s" );
		$fields = self::USER_ID . ', ' . self::BOUGHT_DATE . ', ' . self::BARGAIN_ID;
		$values = "'" . $user_id . "','" . $date . "','" . $bargain_id . "'";
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function getBargainCatalog ($footer=false) {
		$fields = self::BARGAINS . '.' . self::ID . ',' .
					self::BARGAINS . '.' . self::BARGAIN_NAME . ',' . 
					self::BARGAINS . '.' . self::DISCOUNT . ',' .
					self::BARGAINS . '.' . self::HASH . ',' .
					UploadsService::UPLOADS . '.' . UploadsService::PATH; 
		$from = self::BARGAINS . 
				SQLClient::LEFT . SQLClient::JOIN . self::BARGAIN_RELATIONS . 
				SQLClient::ON . self::BARGAIN_RELATIONS . '.' . self::BARGAIN_ID . '=' . 
				self::BARGAINS . '.' . self::ID .
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS . 
				SQLClient::ON . UploadsService::UPLOADS . '.' . UploadsService::ID . '=' .
				self::BARGAIN_RELATIONS . '.' . self::UPLOAD_ID;
		$date = date('Y-m-d');
		$where = self::UNTIL_DATE . '>=' . $date . ' AND ' . self::NUMBER . '>0';
		$orderby = $footer==true ? "RAND()" : self::DISCOUNT . ' ' . SQLClient::DESC;
		$limit = $footer==true ? '0,10' : '0,5';
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , $orderby, $limit );
		if($result != null && isset($result) && count($result) > 0 ){ 
			foreach($result as $key => $value){
				$part = explode('.',$value[UploadsService::PATH]);
				$result[$key][UploadsService::PATH] = $part[0] . '-thumbnail.' . $part[1];
			}
		} else {
			$result = false;
		}
		return $result;
	}
	
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