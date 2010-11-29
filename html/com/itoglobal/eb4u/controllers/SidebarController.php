<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	/**
	 * @param unknown_type $actionParams
	 * @param unknown_type $requestParams
	 * @return $modelAndView
	 */
	public function handleActionRequest($actionParams, $requestParams) {
		$mvc = parent::handleActionRequest($actionParams, $requestParams);
		
		$id = SessionService::getAttribute ( SessionService::USERS_ID );
		if (isset ($id) && $id != null){
			$new_mails = MailService::countNew($id);
			$mvc->addObject ( MailService::NEW_MAILS, $new_mails[MailService::NEW_MAILS]);
		}
		
		//TODO: put navigation handling here
		return $mvc;		
	}
}

?>