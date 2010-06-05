<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class NavigationController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type $actionParams
	 * @param unknown_type $requestParams
	 * @return $modelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		//TODO: put navigation handling here
		return $mvc;		
	}

}

?>