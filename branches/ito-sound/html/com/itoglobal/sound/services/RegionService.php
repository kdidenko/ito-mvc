<?php

class RegionService {
	/**
	 * @var string defining the regions table name
	 */
	const REGIONS = 'regions';
	/**
	 * @var  string defining the id field name
	 */
	const ID = 'id';
	/**
	 * @var  string defining the region_name field name
	 */
	const REGION_NAME = 'region_name';
	
	
	/**
	 * get regions
	 * @return array
	 */
	public static function getRegions (){
		$fields = '*';
		$from = self::REGIONS; 
		# executing the query
		$result = DBClientHandler::getInstance ()->execSelect ( $fields, $from, '', '', '', '' );
		$result = $result != null && isset($result) && count($result) > 0 ? $result : false;
		return $result;
	}
}

?>