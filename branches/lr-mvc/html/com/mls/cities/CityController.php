<?php

require_once ('com/itoglobal/mvc/defaults/BaseActionControllerImpl.php');

class CityController extends BaseActionControllerImpl {
	
	const DATA_FILE = 'cities.xml';
	
	private static $data;
	
	function __construct() {
		$content = file_get_contents(DATA_PATH . self::DATA_FILE);
		self::$data = new SimpleXMLElement($content);
	}
	
	/* 
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		$country = $mvc->getProperty('country');
		$state = $mvc->getProperty('state');
		$xpath = "//country[@name='{$country}']/state[@name='{$state}']/city";
		$res = self::$data->xpath($xpath);
		$cities = array();
		foreach($res as $city) {
			array_push($cities, (string) $city);
		}
		$mvc->addObject('cities', $cities);
		return $mvc;
	}

	
	
}

?>