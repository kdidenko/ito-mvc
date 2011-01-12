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
	 * @var string defining the time_left field name
	 */
	const TIME_LEFT = 'time_left';	
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
	 * @var string defining the crdate field name
	 */
	const CRDATE = 'crdate';
	/**
	 * @var string defining the bought field name
	 */
	const BOUGHT = 'bought';
	
	
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
	
	/**
	 * @var string defining the bought_orders table name
	 */
	const BOUGHT_ORDERS = 'bought_orders';
	/**
	 * @var string defining the bought_date field name
	 */
	const BOUGHT_DATE = 'bought_date';
	/**
	 * @var string defining the bought_price field name
	 */
	const BOUGHT_PRICE = 'bought_price';
	
	
	/*
	ALTER TABLE  `orders` ADD  `bought` BOOL NOT NULL ;
	CREATE TABLE  `bought_orders` (
	 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	 `order_id` INT NOT NULL ,
	 `user_id` INT NOT NULL ,
	 `bought_date` DATETIME NOT NULL ,
	 `bought_price` FLOAT NOT NULL
	) ENGINE = INNODB;

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
	 	SELECT orders. * , category.category_name, subcategory.subcategory_name, regions.region_name, country.country_name, users.username, uploads.path
		FROM orders
		LEFT JOIN category ON category.id = orders.category_id
		LEFT JOIN subcategory ON subcategory.id = orders.subcategory_id
		LEFT JOIN regions ON regions.id = orders.region
		LEFT JOIN country ON country.id = orders.country
		LEFT JOIN users ON users.id = orders.owner
		LEFT JOIN order_relations ON order_relations.order_id = orders.id
		LEFT JOIN uploads ON uploads.id = order_relations.upload_id
		GROUP BY orders.id
		LIMIT 0 , 30
	 * @return array
	 */
	public static function getOrders ($where = NULL, $limit = NULL){
		$fields = self::ORDERS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME . 
				', ' . SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::SUBCAT_NAME . 
				', ' . RegionService::REGIONS . '.' . RegionService::REGION_NAME . 
				', ' . CountryService::COUNTRY . '.' . CountryService::COUNTRY_NAME . 
				', ' . UsersService::USERS . '.' . UsersService::USERNAME .
				', ' . UploadsService::UPLOADS . '.' . UploadsService::PATH;
		$from = self::ORDERS . 
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::ORDERS . '.' . self::CATEGORY_ID . 
				SQLClient::LEFT . SQLClient::JOIN . SubCategoryService::SUBCATEGORY . SQLClient::ON . 
				SubCategoryService::SUBCATEGORY . '.' . SubCategoryService::ID . '=' . 
				self::ORDERS . '.' . self::SUBCATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . RegionService::REGIONS . SQLClient::ON . 
				RegionService::REGIONS . '.' . RegionService::ID . '=' . 
				self::ORDERS . '.' . self::REGION . 
				SQLClient::LEFT . SQLClient::JOIN . CountryService::COUNTRY . SQLClient::ON . 
				CountryService::COUNTRY . '.' . CountryService::ID . '=' . 
				self::ORDERS . '.' . self::COUNTRY .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::ORDERS . '.' . self::OWNER .
				SQLClient::LEFT . SQLClient::JOIN . OrdersService::ORDER_RELATIONS . SQLClient::ON . 
				OrdersService::ORDER_RELATIONS . '.' . OrdersService::ORDER_ID . '=' . 
				self::ORDERS . '.' . self::ID .
				SQLClient::LEFT . SQLClient::JOIN . UploadsService::UPLOADS . SQLClient::ON . 
				UploadsService::UPLOADS . '.' . UploadsService::ID . '=' . 
				OrdersService::ORDER_RELATIONS . '.' . OrdersService::UPLOAD_ID;
		$groupBy = OrdersService::ORDERS . '.' . OrdersService::ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $groupBy, '', $limit );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function checkOrders (){
		$date = date("Y-m-d h:m:s");
		$fields = self::ORDERS . '.' . self::ID . ',' .  self::ORDERS . '.' . self::OWNER . ',' .  
				self::ORDERS . '.' . self::ORDER_NAME . ',' . self::ORDERS . '.' . self::HASH;
		$from = self::ORDERS;
		$where = self::BOUGHT . '=0 AND ' . self::UNTIL_DATE . "<'" . $date . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function buyOrder ($order_id){
		$from = self::ORDERS;
		$where = self::ID . " = '" . $order_id . "'";
		$fields = self::BOUGHT;
		$vals = 1;
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
		$bids = self::getBids($order_id);
		if(isset($bids)&&$bids!=NULL){
			$date = gmdate ( "Y-m-d H:i:s" );
			$id = SessionService::getAttribute ( SessionService::USERS_ID );
			$into = self::BOUGHT_ORDERS;
			$fields = self::BOUGHT_PRICE . ', ' . self::USER_ID . ', ' . self::ORDER_ID . ', ' . 
						self::BOUGHT_DATE;
			$values = 	"'" . $bids[0][self::BID] . "','" . $id . "','" . 
						$order_id . "','" .	$date . "'";
			$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		}
		return $bids;
	}
		
	public static function countOrders ($where = NULL){
		$fields =  SQLClient::COUNT . "(" . self::ID . ") as " . self::ORDERS;
		$from = self::ORDERS;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
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
		$fields =  self::ORDERS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME .
					', ' . UsersService::USERS . '.' . UsersService::USERNAME;
		$from = self::ORDER_BOOKMARKS . 
				SQLClient::LEFT . SQLClient::JOIN . self::ORDERS . 
				SQLClient::ON . self::ORDERS . '.' . self::ID . '=' . 
				self::ORDER_BOOKMARKS . '.' . self::ORDER_ID .
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::ORDERS . '.' . self::CATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::ORDERS . '.' . self::OWNER;
		$where = self::USER_ID . "='" . $user_id . "'"; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function getBoughtOrder($user_id){
		$fields =  self::ORDERS . '.*, ' . CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME .
					', ' . UsersService::USERS . '.' . UsersService::USERNAME . 
					', ' . self::BOUGHT_ORDERS . '.' . self::BOUGHT_PRICE .
					', ' . self::BOUGHT_ORDERS . '.' . self::BOUGHT_DATE;
		$from = self::BOUGHT_ORDERS . 
				SQLClient::LEFT . SQLClient::JOIN . self::ORDERS . 
				SQLClient::ON . self::ORDERS . '.' . self::ID . '=' . 
				self::BOUGHT_ORDERS  . '.' . self::ORDER_ID .
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::ORDERS . '.' . self::CATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::ORDERS . '.' . self::OWNER;
		$where = self::USER_ID . "='" . $user_id . "'"; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	
	public static function getCurrentOrders ($user_id){
		$date = date("Y-m-d h:m:s");
		$fields =  self::ORDERS . '.*, min(' . self::BIDS . '.' . self::BID . ') as bids, '. CategoryService::CATEGORY . '.' . CategoryService::CAT_NAME .
					', ' . UsersService::USERS . '.' . UsersService::USERNAME;
/*
		SELECT *, min(bids.bid)
FROM  `orders` 
LEFT JOIN bids ON bids.order_id = orders.id
WHERE bids.user_id =42
GROUP BY bids.order_id
*/
		$from = self::BIDS . 
				SQLClient::LEFT . SQLClient::JOIN . self::ORDERS . 
				SQLClient::ON . self::ORDERS . '.' . self::ID . '=' . 
				self::BIDS . '.' . self::ORDER_ID .
				SQLClient::LEFT . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . CategoryService::CATEGORY . '.' . CategoryService::ID . '=' . 
				self::ORDERS . '.' . self::CATEGORY_ID .
				SQLClient::LEFT . SQLClient::JOIN . UsersService::USERS . SQLClient::ON . 
				UsersService::USERS . '.' . UsersService::ID . '=' . 
				self::ORDERS . '.' . self::OWNER;
		$where = self::USER_ID . "='" . $user_id . "' AND " . self::UNTIL_DATE . ">='" . $date . "'" ; 
		$group = self::BIDS . '.' . self::ORDER_ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, $group, '', '' );
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
					self::HASH . ', ' . self::PRICE . ', ' . self::CRDATE;
		$values = "'" . $id . "','" . $data[self::ORDER_NAME] . "','" . 
					$data[self::ORDER_DESC] . "','" . $data[self::CATEGORY_ID] . "','" . 
					$data[self::SUBCATEGORY_ID] . "','" . 
					$data[self::STREET] . "','" . $data[self::ZIP] ."','" .
					$data[self::CITY] . "','" . $data[self::REGION] . "','" . 
					$data[self::COUNTRY] . "','" . 
					$data[self::FROM_DATE] . "','" . $data[self::UNTIL_DATE] ."','" .
					$data[self::IMP_FROM_DATE] . "','" . $data[self::IMP_UNTIL_DATE] ."','" . 
					$hash ."','" . $data[self::PRICE] ."','" . $date . "'";
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