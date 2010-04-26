<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type unknown_type $actionParams
	 * @param unknown_type unknown_type $requestParams
	 * @return ModelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		return SessionService::isUserLogedin ( $actionParams, $requestParams ) ? 
				SessionService::isUserLogedin ( $actionParams, $requestParams ) : 
						SecureActionControllerImpl::handleActionRequest ( $actionParams, $requestParams );
	}
	
	public function handleHome($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	
	public function handleSchools($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleTrainings($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleCommunity($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleDiscussions($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleAbout($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleMyResponses($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleValuateResponses($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleMyChallenges($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
	public function handleMessages($actionParams, $requestParams) {
		return $this->handleActionRequest($actionParams, $requestParams);
	}
}

?>