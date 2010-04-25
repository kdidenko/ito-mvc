<?php

require_once "com/itoglobal/mvc/defaults/SecureActionController.php";

class SecureActionControllerImpl extends BaseActionControllerImpl implements SecureActionController {
	
	/**
	 * Basic implementation of onSignedOff method defined by
	 * com.itoglobal.mvc.defaults.SecureActionController interface.
	 *
	 * @see SecureActionController->onSignedOff($actionParams)
	 */
	public function onSignedOff($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_SIGNED_OFF );
	}
	
	/**
	 * Basic implementation of onAbort method defined by
	 * com.itoglobal.mvc.defaults.SecureActionController interface.
	 *
	 * @see SecureActionController->onLoggedIn($actionParams)
	 */
	public function onLoggedIn($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_LOGGED_IN );
	}

}

?>