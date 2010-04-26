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
		return $this->handleActionRequest($actionParams, $requestParams);
	}

}

?>