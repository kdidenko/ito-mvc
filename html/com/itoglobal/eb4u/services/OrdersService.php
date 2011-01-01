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
	 * @var string defining the imp_from_date field name
	 */
	const IMP_FROM_DATE = 'imp_from_date';
	/**
	 * @var string defining the imp_until_date field name
	 */
	const IMP_UNTIL_DATE = 'imp_until_date';
	/**
	 * @var string defining the status field name
	 */
	const HASH = 'hash';
	/**
	 * @var string defining the order_price field name
	 */
	const PRICE = 'order_price';
	const FROM_PRICE = "price_from";
	const UNTIL_PRICE = "price_until";
	
	/**
	 * @var string defining the order_bookmarks table name
	 */
	const ORDER_BOOKMARKS = 'order_bookmarks';
	/**
	 * @var string defining the order_relations table name
	 */
	const ORDER_RELATIONS = 'order_relations';
	/**
	 * @var string defining the order_id field name
	 */
	const ORDER_ID = 'order_id';
	/**
	 * @var string defining the upload_id field name
	 */
	const UPLOAD_ID = 'upload_id';
	/**
	 * @var string defining the bids table name
	 */
	const BIDS = 'bids';
	/**
	 * @var string defining the user_id field name
	 */
	const USER_ID = 'user_id';
	/**
	 * @var string defining the bid field name
	 */
	const BID = 'bid';
	/**
	 * @var string defining the date field name
	 */
	const DATE = 'date';
	
	/*
	CREATE TABLE  `bids` (
	 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	 `bid` FLOAT NOT NULL ,
	 `user_id` INT NOT NULL ,
	 `order_id` INT NOT NULL
	) ENGINE = INNODB;
	
	CREATE TABLE  `order_bookmarks` (
	 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	 `order_id` INT NOT NULL ,
	 `user_id` INT NOT NULL
	) ENGINE = INNODB;
		
	
	CREATE TABLE  `order_relations` (
	 `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
	 `order_id` INT( 11 ) NOT NULL ,
	 `upload_id` INT( 11 ) NOT NULL ,
	PRIMARY KEY (  `id` )
	) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin AUTO_INCREMENT =3;
	
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
	public static function getOrders ($where = NULL){
		$fields = self::ORDERS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME . 
				', ' . RegionService::REGIONS . '.' . RegionService::REGION_NAME . 
				', ' . CountryService::COUNTRY . '.' . CountryService::COUNTRY_NAME . 
				', ' . UsersService::USERS . '.' . UsersService::USERNAME;
		$from = self::ORDERS . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::ORDERS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY .	SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::ORDERS . '.' . self::SUBCATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . RegionService::REGIONS .	SQLClient::ON . 
				RegionService::REGIONS . '.' . RegionService::ID . '=' . 
				self::ORDERS . '.' . self::REGION . 
				SQLClient::LEFT . SQLClient::JOIN . CountryService::COUNTRY .	SQLClient::ON . 
				CountryService::COUNTRY . '.' . CountryService::ID . '=' . 
				self::ORDERS . '.' . self::COUNTRY .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS .	SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::ORDERS . '.' . self::OWNER;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function getOrderImgs ($order_id){
		$fields = self::ORDER_RELATIONS . '.*, ' . UploadsService::UPLOADS . '.' . UploadsService::PATH;
		$from = self::ORDER_RELATIONS . 
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS . 
				SQLClient::ON . UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				self::ORDER_RELATIONS . '.' . self::UPLOAD_ID;
		$where = self::ORDER_ID . "='" . $order_id . "'"; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function createBookmark ($order_id, $user){
		$into = self::ORDER_BOOKMARKS;
		$fields = self::ORDER_ID . ', ' . self::USER_ID;
		$values = "'" . $order_id . "','" . $user . "'";
		$bookmark_id = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function getOrderBookmarks ($user_id){
		$fields = self::ORDERS . '.*';
		$from = self::ORDER_BOOKMARKS . 
				SQLClient::LEFT . SQLClient::JOIN . self::ORDERS . 
				SQLClient::ON . self::ORDERS . '.' . self::ID . '=' . 
				self::ORDER_BOOKMARKS . '.' . self::ORDER_ID;
		$where = self::USER_ID . "='" . $user_id . "'"; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function makeBid ($hash, $bid){
		$where = self::HASH . "='" . $hash . "'";  
		$orders = self::getOrders($where);
		$order_id = $orders[0][self::ID];
		$date = gmdate ( "Y-m-d H:i:s" );
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		$into = self::BIDS;
		$fields = self::BID . ', ' . self::USER_ID . ', ' . self::ORDER_ID . ', ' . 
					self::DATE;
		$values = 	"'" . $bid . "','" . $id . "','" . 
					$order_id . "','" .	$date . "'";
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function getBids($order_id, $where = null){
		$fields = '*';
		$from = self::BIDS;
		$where = isset($where)&& $where!=NULL ? $where . ' AND ' : NULL; 
		$where .= self::ORDER_ID . "='" . $order_id . "'"; 
		$order_by = self::BID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $order_by, '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function setOrders ($id, $data, $paths){
		$into = self::ORDERS;
		$date = gmdate ( "Y-m-d H:i:s" );
		$hash = md5($date . $id);
		$fields = self::OWNER . ', ' . self::ORDER_NAME . ', ' . self::ORDER_DESC . ', ' . 
					self::CATEGORY_ID . ', ' . self::SUBCATEGORY_ID . ', ' . 
					self::STREET . ', ' . self::ZIP . ', ' . self::CITY . ', ' . self::REGION . ', ' . 
					self::COUNTRY . ', ' . self::FROM_DATE . ', ' .	self::UNTIL_DATE . ', ' . 
					self::IMP_FROM_DATE . ', ' . self::IMP_UNTIL_DATE . ', ' . 
					self::HASH . ', ' . self::PRICE;
		$values = "'" . $id . "','" . $data[self::ORDER_NAME] . "','" . 
					$data[self::ORDER_DESC] . "','" . $data[self::CATEGORY_ID] . "','" . 
					$data[self::SUBCATEGORY_ID] . "','" . 
					$data[self::STREET] . "','" . $data[self::ZIP] ."','" .
					$data[self::CITY] . "','" . $data[self::REGION] . "','" . 
					$data[self::COUNTRY] . "','" . 
					$data[self::FROM_DATE] . "','" . $data[self::UNTIL_DATE] ."','" .
					$data[self::IMP_FROM_DATE] . "','" . $data[self::IMP_UNTIL_DATE] ."','" . 
					$hash ."','" . $data[self::PRICE] . "'";
					
		$order_id = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		foreach($paths as $key => $path){
			$upload_id = UploadsService::setUploadsPath($path);
			$into = self::ORDER_RELATIONS;
			$fields = self::ORDER_ID . ',' . self::UPLOAD_ID;
			$values = "'" . $order_id . "', '" . $upload_id . "'";
			DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		}
	}
}

?>