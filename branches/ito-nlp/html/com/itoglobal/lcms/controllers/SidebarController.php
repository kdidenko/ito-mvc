<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	const USERS_ID = 'user_id';
	/**
	 * @param unknown_type unknown_type $actionParams
	 * @param unknown_type unknown_type $requestParams
	 * @return ModelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$result = null;
		$sid = SessionService::getAttribute ( self::USERS_ID );
		if (! isset ( $sid )) {
			$result = RequestDispatcher::getInstance ()->dispatchActionRequest ( $this->onSignedOff ( $actionParams ) );
		} else {
			$result = parent::handleActionRequest ( $actionParams, $requestParams );
		}
		return $result;
	}

}

?>