<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$firstname = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( SessionService::FIRSTNAME, $firstname ) : null;
		$lastname = SessionService::getAttribute ( SessionService::LASTNAME );
		isset ( $lastname ) ? $mvc->addObject ( SessionService::LASTNAME, $lastname ) : null;
		return $mvc;
	}
	
	public function handleSchools($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleTrainings($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleCommunity($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleDiscussions($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleAbout($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMyResponses($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleValuateResponses($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMyChallenges($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMessages($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleManageSchools($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleManageExercises($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleManageUsers($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$firstname = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( SessionService::FIRSTNAME, $firstname ) : null;
		$lastname = SessionService::getAttribute ( SessionService::LASTNAME );
		isset ( $lastname ) ? $mvc->addObject ( SessionService::LASTNAME, $lastname ) : null;
		return $mvc;
	}
}

?>