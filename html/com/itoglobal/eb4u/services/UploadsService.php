<?php

class UploadsService {
	/**
	 * @var string defining the uploads table name
	 */
	const UPLOADS = 'uploads';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the path field name
	 */
	const PATH = 'path';
	
	public static function getUploadsPath() {
		$fields = '*';
		$from = self::UPLOADS;
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '' , '', '' );
		$result = $result != null && isset($result) ? $result : false;
		return $result;
	}
	
	public static function setUploadsPath($path) {
		$fields = self::UPLOADS . '.' . self::PATH;
		$values = "'" . $path . "'";
		$into = self::UPLOADS;
		# executing the query
		$result = DBClientHandler::getInstance ()->execInsert ( $fields, $values, $into );
		return $result;
	}

}

?>