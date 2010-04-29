<?php

class SchoolService {
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the schools table name
	 */
	const SCHOOLS_TABLE = 'schools';
	/**
	 * @var  string defining the alias field name
	 */
	const ALIAS = 'alias';
	/**
	 * @var  string defining the caption field name
	 */
	const CAPTION = 'caption';
	/**
	 * @var  string defining the description field name
	 */
	const DESCRIPTION = 'description';
	/**
	 * @var  string defining the avatar field name
	 */
	const AVATAR = 'avatar';
	
	/**
	 * Populates the complete list of existing schools. 
	 * @return mixed the schools list
	 */
	public static function getSchoolsList() {
		$result = null;
		$fields = self::ID . ', ' . self::ALIAS . ', ' . self::CAPTION . ', ' . self::DESCRIPTION . ', ' . self::AVATAR;
		$from = self::SCHOOLS_TABLE;
		$where = null;
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, $where, '', '', '' );
		return $result;
	}

}

?>