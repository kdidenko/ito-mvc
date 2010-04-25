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

}

?>