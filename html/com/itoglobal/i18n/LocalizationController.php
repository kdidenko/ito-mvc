<?php
require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class LocalizationController extends SecureActionControllerImpl {

	public function switchLocale($actionParams, $requestParams){
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$session = SessionService::startSession ();
		SessionService::setAttribute ( SessionService::LOCALE, $requestParams[SessionService::LOCALE] );
		//echo $_SERVER['HTTP_REFERER'];
		$this->forwardActionRequest ( $_SERVER['HTTP_REFERER'] );
		//exit;
		return true;
	}
}
?>