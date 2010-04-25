<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type unknown_type $actionParams
	 * @param unknown_type unknown_type $requestParams
	 * @return ModelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		return 	(SessionService::isUserLogin( $actionParams, $requestParams ) == true) ? SessionService::isUserLogin( $actionParams, $requestParams ) : SecureActionControllerImpl::handleActionRequest ( $actionParams, $requestParams );
	}

}

?>