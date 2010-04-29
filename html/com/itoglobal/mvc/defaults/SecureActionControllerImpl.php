<?php

require_once "com/itoglobal/mvc/defaults/BaseActionControllerImpl.php";
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
	
	/**
	 * Secure actions handling method implementation. Qualifies the
	 * minimum required for Model View Controlling and handles views based on User Role specified.
	 * 
	 * @param mixed $actionParams - action config parameters
	 * @param mixed $requestParams - request parameters
	 * @return ModelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$result = parent::handleActionRequest ( $actionParams, $requestParams );
		$template = $actionParams->template;
		if (isset ( $template->role )) {
			$role = SessionService::getRole ();
			foreach ( $template->role as $key => $value ) {
				if ($role == ( string ) $value ['type']) {
					$result->setTemplate ( $value );
					break;
				}
			}
		}
		return $result;
	}

}

?>