<?php

require_once ('com/itoglobal/mvc/defaults/BaseActionControllerImpl.php');

class ContentController extends BaseActionControllerImpl {
	
	/* (non-PHPdoc)
	 * @see BaseActionControllerImpl::handleActionRequest()
	 */
	public function handleHomepage($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest($actionParams, $requestParams);
		// populate latest listings
		$cnt = $mvc->getProperty('listingsCnt');
		$listings = ListingService::getLatest($cnt);
		$mvc->addObject('listings', $listings);
		// populate latest news
		$cnt = $mvc->getProperty('newsCnt');
		$news = NewsService::getLatest($cnt);
		$mvc->addObject('news', $news);
		return $mvc;
	}

	
	

}

?>