<?php

require_once "com/itoglobal/mvc/defaults/BaseActionControllerImpl.php";
require_once "com/itoglobal/mvc/defaults/SecureActionController.php";

class SecureActionControllerImpl extends BaseActionControllerImpl implements SecureActionController {
	
	const ROLE_DELIMETER = ',';
	
	const SIGNED_OFF_MESSAGE = 'Your session has expired, please login';
	
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
	 * Basic implementation of onLoggedIn method defined by
	 * com.itoglobal.mvc.defaults.SecureActionController interface.
	 *
	 * @see SecureActionController->onLoggedIn($actionParams)
	 */
	public function onLoggedIn($actionParams) {
		return self::getLocationOnCondition ( $actionParams, self::MVC_ON_LOGGED_IN );
	}
	
	/**
	 * Basic implementation of onRole method defined by
	 * com.itoglobal.mvc.defaults.SecureActionController interface.
	 *
	 * @see SecureActionController->onLoggedIn($actionParams)
	 */
	public function onRole($actionParams) {
		return self::getMethodOnCondition ( $actionParams, self::MVC_ON_ROLE );
	}
	
	/**
	 * Retreives the method name parameter for a specified action processing
	 * state condition using action configuration model object.
	 *
	 * @param $actionParams
	 * @param $condition
	 * @return unknown_type
	 */
	public function getMethodOnCondition($actionParams, $condition) {
		$result = null;
		if ($actionParams && ($forwards = $actionParams->forwards)) {
			foreach ( $forwards->target as $opt ) {
				if ($opt ['condition'] && $condition == ( string ) $opt ['condition']) {
					$result = ( string ) $opt ['class'];
					break;
				}
			}
		}
		return $result;
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
		$role = SessionService::getRole ();
		$template = $actionParams->template;		
		if (isset ( $actionParams ['roles'] )) {
			$permitted = explode ( self::ROLE_DELIMETER, ( string ) $actionParams ['roles'] );
			if (in_array ( $role, $permitted )) {
				$result->setTemplate ( $template );
			} else {
				//TODO: forward doesn't contain ModelAndView Object for now.
				$result->addObject ( UsersService::ERROR, self::SIGNED_OFF_MESSAGE );
				$location = self::onSignedOff ( $actionParams );
				self::forwardActionRequest ($location);
			}
		} else if (isset ( $template->role )) {
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