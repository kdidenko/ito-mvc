<?php

class SubCategoryService {
	/**
	 * @var string defining the mails table name
	 */
	const SUBCATEGORY = 'subcategory';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the subject field name
	 */
	const SUBCAT_NAME = 'subcategory_name';
	/**
	 * @var  string defining the subject field name
	 */
	const CAT_ID = 'category_id';
	
	const NEW_SUBCAT = 'newSubCategory';
	
	public static function getSubCategories ($where = NULL){
		$fields = self::SUBCATEGORY . '.*, ' . CategoryService::CATEGORY . '.' . 
				CategoryService::CAT_NAME;
		$from = self::SUBCATEGORY . SQLClient::JOIN . CategoryService::CATEGORY . 
				SQLClient::ON . self::SUBCATEGORY . '.' . self::CAT_ID . '=' .
				CategoryService::CATEGORY . '.' . CategoryService::ID;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function createNewSubCat ($subcategory, $category){
		$into = self::SUBCATEGORY;
		$fields = self::SUBCAT_NAME . "," . self::CAT_ID;
		$values = "'" . $subcategory . "','" . $category . "'";
		# executing the query
		DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
	}
	
	public static function getSubcatByCat ($cat_id){
		$fields = '*';
		$from = self::SUBCATEGORY;
		# executing the query
		$where = self::CAT_ID . '=' . $cat_id;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}

	public static function updateSubCategory ($id, $fields, $vals){
		$from = self::SUBCATEGORY;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $hash the mail id
	 * @return mail data
	 */
	public static function getSubCategory($id) {
		$fields = '*';
		$from = self::SUBCATEGORY;
		$where = self::ID . '=' . $id;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	/**
	 * delete mail from trash
	 * @param integer $id the mail id
	 */
	public static function deleteSubCategory($id) {
		# setting the query variables
		$from = self::SUBCATEGORY;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
	}	

}

?>