<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type unknown_type $actionParams
	 * @param unknown_type unknown_type $requestParams
	 * @return ModelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$result = null;
		$sid = SessionService::getAttribute ( SessionService::USERS_ID );
		if (! isset ( $sid )) {
			$result = RequestDispatcher::getInstance ()->dispatchActionRequest ( $this->onSignedOff ( $actionParams ) );
		} else {
			$result = parent::handleActionRequest ( $actionParams, $requestParams );
		}
		return $result;
	}

}

?>