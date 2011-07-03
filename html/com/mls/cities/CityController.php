<?php

require_once ('com/itoglobal/mvc/defaults/BaseActionControllerImpl.php');

class CityController extends BaseActionControllerImpl {
	
	/* 
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest ( $actionParams, $requestParams );
		$country = $mvc->getProperty ( 'country' );
		$state = $mvc->getProperty ( 'state' );
		$cities = LocationService::citiesList ( $country, $state );
		$mvc->addObject ( 'cities', $cities );
		return $mvc;
	}

}

?>