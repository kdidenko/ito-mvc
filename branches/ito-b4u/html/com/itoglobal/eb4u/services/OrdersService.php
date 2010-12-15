<?php

class OrdersService {
	/**
	 * @var string defining the orders table name
	 */
	const ORDERS = 'orders';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the owner field name
	 */
	const OWNER = 'owner';
	/**
	 * @var  string defining the order_name field name
	 */
	const ORDER_NAME = 'order_name';
	/**
	 * @var  string defining the order_desc field name
	 */
	const ORDER_DESC = 'order_desc';
	/**
	 * @var string defining the category_id field name
	 */
	const CATEGORY_ID = 'category_id';
	/**
	 * @var string defining the subcategory_id field name
	 */
	const SUBCATEGORY_ID = 'subcategory_id';
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
	 * @var string defining the from_date field name
	 */
	const FROM_DATE = 'from_date';
	/**
	 * @var string defining the until_date field name
	 */
	const UNTIL_DATE = 'until_date';
	/**
	 * @var string defining the status field name
	 */
	const HASH = 'hash';
	
	/*
	
	CREATE TABLE  `orders` (
	 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	 `category_id` INT NOT NULL ,
	 `subcategory_id` INT NOT NULL ,
	 `order_name` VARCHAR( 255 ) NOT NULL ,
	 `order_desc` TEXT NOT NULL ,
	 `street` VARCHAR( 255 ) NOT NULL ,
	 `zip` VARCHAR( 255 ) NOT NULL ,
	 `city` VARCHAR( 255 ) NOT NULL ,
	 `region` INT NOT NULL ,
	 `country` INT NOT NULL ,
	 `from_date` DATE NOT NULL ,
	 `until_date` DATE NOT NULL
	) ENGINE = INNODB;
	ALTER TABLE  `orders` ADD  `hash` VARCHAR( 32 ) NOT NULL ;
	
	*/
	
	/**
	 * get Countries
	 * @return array
	 */
	public static function getOrders (){
		$fields = self::COUNTRY . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME . 
				', ' . RegionService::REGIONS . '.' . RegionService::REGION_NAME . 
				', ' . CountryService::COUNTRY . '.' . CountryService::COUNTRY_NAME;
		$from = self::COUNTRY . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::COUNTRY . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::COUNTRY . '.' . self::SUBCATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . RegionService::REGIONS .	SQLClient::ON . 
				RegionService::REGIONS . '.' . RegionService::ID . '=' . 
				self::COUNTRY . '.' . self::REGION . 
				SQLClient::LEFT . SQLClient::JOIN . CountryService::COUNTRY .	SQLClient::ON . 
				CountryService::COUNTRY . '.' . CountryService::ID . '=' . 
				self::COUNTRY . '.' . self::COUNTRY; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function setOrders ($id, $data, $path){
		$into = self::COUNTRY;
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $id);
		$fields = self::OWNER . ', ' . self::ORDER_NAME . ', ' . self::ORDER_DESC . ', ' . 
					self::CATEGORY_ID . ', ' . self::SUBCATEGORY_ID . ', ' . 
					self::STREET . ', ' . self::ZIP . ', ' . self::CITY . ', ' . self::REGION . ', ' . 
					self::COUNTRY . ', ' . self::FROM_DATE . ', ' .	self::UNTIL_DATE . ', ' . 
					self::HASH;
		$values = "'" . $id . "','" . $data[self::ORDER_NAME] . "','" . 
					$data[self::ORDER_DESC] . "','" . $data[self::CATEGORY_ID] . "','" . 
					$data[self::SUBCATEGORY_ID] . "','" . 
					$data[self::STREET] . "','" . $data[self::ZIP] ."','" .
					$data[self::CITY] . "','" . $data[self::REGION] . "','" . 
					$data[self::COUNTRY] . "','" . 
					$data[self::FROM_DATE] . "','" . $data[self::UNTIL_DATE] ."','" . 
					$hash . "'";
					
		$order_id = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		//$upload_id = UploadsService::setUploadsPath($path);
		//$into = self::BARGAIN_RELATIONS;
		//$fields = self::BARGAIN_ID . ',' . self::UPLOAD_ID;
		//$values = "'" . $order_id . "', '" . $upload_id . "'";
		//DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
}

?>