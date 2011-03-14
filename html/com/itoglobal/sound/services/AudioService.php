<?php

class AudioService {
	/**
	 * @var string defining the bargains table name
	 */
	const AUDIO = 'bargains';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the user_id field name
	 */
	const AUTHOR = 'author';
	/**
	 * @var  string defining the bargain_name field name
	 */
	const AUDIO_NAME = 'name';
	/**
	 * @var string defining the bargain_desc field name
	 */
	const AUDIO_FILE = 'audio_file';
	/**
	 * @var string defining the category_id field name
	 */
	const IMAGE_FILE = 'image_file';
	/**
	 * @var string defining the subcategory_id field name
	 */
	const GENRE = 'genre';
	/**
	 * @var string defining the usual_price field name
	 */
	const TYPE = 'type';
	/**
	 * @var string defining the discount field name
	 */
	const CRDATE = 'crdate';

	/*
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
	*/
	/**
	 * delete bargain from db
	 * @param integer $id the mail id
	 *//*
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
	*/
	/**
	 * Retrieves the users bargains by specified user id.
	 * @param integer $user the user id.
	 * @return mixed bargains data or null if user with such id does not exists. 
	 *//*
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
				self::BOUGHT_BARGAIN . '.' . self::USER_ID;
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
		$where = self::UNTIL_DATE . ">='" . $date . "' AND " . self::NUMBER . '>0' . " AND " . self::STATUS . '=1';
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
	*/

}

?>