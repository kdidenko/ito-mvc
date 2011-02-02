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
	/**
	 * @var  string defining the path field name
	 */
	const PATH2 = 'path2';
	
	public static function getUploadsPath($where = NULL) {
		$fields = '*';
		$from = self::UPLOADS;
		$where = self::ID . '=' . $where;
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

	public static function delUploads($upload_id, $path) {
		$from = self::UPLOADS;
		$where = self::ID . " = " . $upload_id;
		# executing the query
		DBClientHandler::getInstance ()->execDelete($from, $where, '', '');
		StorageService::deleteDirectory($path);
	}
	
}

?>