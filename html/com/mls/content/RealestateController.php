<?php

require_once ('com/itoglobal/mvc/defaults/BaseActionControllerImpl.php');

class RealestateController extends BaseActionControllerImpl {
	
	/* (non-PHPdoc)
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleAddRequest($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$country = $mvc->getProperty ( 'country' );
		$state = $mvc->getProperty ( 'state' );
		$regions = LocationService::citiesList ( $country, $state );
		$mvc->addObject ( 'regions', $regions );
		return $mvc;
	}
	
	/* (non-PHPdoc)
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleSearchRequest($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$country = $mvc->getProperty ( 'country' );
		$state = $mvc->getProperty ( 'state' );
		$regions = LocationService::citiesList ( $country, $state );
		$mvc->addObject ( 'regions', $regions );
		return $mvc;
	}
	
	/* (non-PHPdoc)
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleListingsAll ($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$cnt = $mvc->getProperty('listingsCnt');
		$listings = ListingService::getLatest($cnt);
		$mvc->addObject('listings', $listings);
		return $mvc;
	}

	
	

}

?>