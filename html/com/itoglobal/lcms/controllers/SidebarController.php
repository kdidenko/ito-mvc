<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	
	/**
	 * Secure actions handling method implementation. Qualifies the
	 * minimum required for sidebar Model View Controlling. 
	 *
	 * @see BaseActionController->handleActionRequest($actionParams, $requestParams)
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		return SessionService::isUserLogedin ( $actionParams, $requestParams ) ? 
				SessionService::isUserLogedin ( $actionParams, $requestParams ) : 
						SecureActionControllerImpl::handleActionRequest ( $actionParams, $requestParams );
	}
	
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$sid = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset( $sid )? $mvc->addObject ( SessionService::FIRSTNAME, $sid ): null;
		
		return $mvc;
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