<?php

class CategoriesService {
	/**
	 * @var string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var string defining the name field name
	 */
	const NAME = 'name';
	/**
	 * @var string defining the categories table name
	 */
	const CATEGORIES_TABLE = 'categories';
	
	public static function getCategoriesList($where = null) {
		$fields = self::ID . ', ' . self::NAME;
		$from = self::CATEGORIES_TABLE;
		isset ( $where ) ? $where : '';
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		return $result;
	}
	
	public static function updateFields($id, $fields, $vals) {
		# setting the query variables
		$from = self::CATEGORIES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function deleteCategories($id) {
		# setting the query variables
		$from = self::CATEGORIES_TABLE;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete ( $from, $where, '', '' );
	}

}

?>