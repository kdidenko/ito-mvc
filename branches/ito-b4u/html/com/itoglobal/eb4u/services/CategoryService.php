<?php

class CategoryService {
	/**
	 * @var string defining the mails table name
	 */
	const CATEGORY = 'category';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the subject field name
	 */
	const CAT_NAME = 'category_name';

	public static function getCategories ($where = NULL){
		$fields = '*';
		$from = self::CATEGORY;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	

	public static function updateCategory ($id, $fields, $vals){
		$from = self::CATEGORY;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	/**
	 * Retrieves mail by specified mail id.
	 * @param integer $hash the mail id
	 * @return mail data
	 */
	public static function getCategory($id) {
		$fields = '*';
		$from = self::CATEGORY;
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
	public static function deleteCategory($id) {
		# setting the query variables
		$from = self::CATEGORY;
		$where = self::ID . " = '" . $id . "'";
		$orderBy = null;
		$limit = null;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, $orderBy, $limit);
	}	

}

?>