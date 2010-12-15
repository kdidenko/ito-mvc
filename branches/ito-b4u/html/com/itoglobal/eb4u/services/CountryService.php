<?php

class CountryService {
	/**
	 * @var string defining the country table name
	 */
	const COUNTRY = 'country';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the country_name field name
	 */
	const COUNTRY_NAME = 'country_name';
	
	
	/**
	 * get Countries
	 * @return array
	 */
	public static function getCountries (){
		$fields = '*';
		$from = self::COUNTRY; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
}

?>