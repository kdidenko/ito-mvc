<?php

class StaticBlockService {
	/**
	 * @var string defining the static_block table name
	 */
	const STATIC_BLOCK = 'static_block';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the block_title field name
	 */
	const BLOCK_TITLE = 'block_title';
	/**
	 * @var string defining the block_desc field name
	 */
	const BLOCK_DESC = 'block_desc';
	
	const SAVE = 'save';
	const SAVE_CONTINUE = 'save_continue';
	
	public static function getBlocks ($where = NULL){
		$fields = '*';
		$from = self::STATIC_BLOCK;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
	
	public static function updateBlock ($id, $fields, $vals){
		$from = self::STATIC_BLOCK;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execUpdate ( $fields, $from, $vals, $where, '', '' );
	}
	
	public static function getBlock($id) {
		$fields = '*';
		$from = self::STATIC_BLOCK;
		$where = self::ID . "='" . $id . "'";
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '' , '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result[0] : false;
		return $result;
	}
	
	public static function createBlock($title, $body){
		$into = self::STATIC_BLOCK;
		$title = htmlspecialchars($title, ENT_QUOTES);
		$body = htmlspecialchars($body, ENT_QUOTES);
		$fields = self::BLOCK_TITLE . ', ' . self::BLOCK_DESC; 
		$values = "'" . $title . "', '" . $body . "'";
		//print_r($fields);
		//print_r($values);
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into);
	} 
	
	public static function deleteBlocks($string){
		if (isset($string) && count($string)>0){
			$array = explode(',', $string);
			foreach ($array as $mail){
				self::deleteBlock($mail);
			}
		}
	}
	
	/**
	 * delete block from db
	 * @param integer $id the block id
	 */
	public static function deleteBlock($id) {
		# setting the query variables
		$from = self::STATIC_BLOCK;
		$where = self::ID . " = '" . $id . "'";
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
	}	

}

?>