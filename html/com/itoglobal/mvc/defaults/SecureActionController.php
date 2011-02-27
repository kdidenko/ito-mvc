<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionController.php';

interface SecureActionController extends BaseActionController {
	
	/* @final constant: specifies when current session state is off */
	const MVC_ON_SIGNED_OFF = 'onsignedoff';
	
	/* @final constant: specifies when current session state in on */
	const MVC_ON_LOGGED_IN = 'onloggedin';
	
	/* @final constant: specifies when role was strictly specified */
	const MVC_ON_ROLE = 'onrole';	
	
	/**
	 * Optionally, resolves the location to forward the request
	 * in case of session state is off.
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onSignedOff($actionParams);
	
	/**
	 * Optionally, resolves the location to forward the request
	 * in case of session state is on.
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onLoggedIn($actionParams);
	
	/**
	 * Optionally, verifies if the role was strictly defined 
	 *
	 * @param $actionParams
	 * @return $location
	 */
	public function onRole($actionParams);	

}

?>