<?php

class LocationService {

	private static $config = null;
	
	const DATA_FILE = 'cities.xml';	
	
	private static function load(){
		if (!self::$config) {
			$content = file_get_contents(DATA_PATH . self::DATA_FILE);
			self::$config = new SimpleXMLElement($content);			
		}
	}
	
	public static function citiesList($country, $state) {
		self::load();
		$xpath = "//country[@name='{$country}']/state[@name='{$state}']/city";
		$res = self::$config->xpath($xpath);
		$cities = array();
		foreach($res as $city) {
			array_push($cities, (string) $city);
		}
		return $cities;
	}
}

?>