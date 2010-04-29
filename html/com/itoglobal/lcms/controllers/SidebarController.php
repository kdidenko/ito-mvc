<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SidebarController extends SecureActionControllerImpl {
	
	public function handleHome($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		$sid = SessionService::getAttribute ( SessionService::FIRSTNAME );
		isset ( $sid ) ? $mvc->addObject ( SessionService::FIRSTNAME, $sid ) : null;
		return $mvc;
	}
	
	public function handleHelp($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleSchools($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	
	public function handleNewSchool($actionParams, $requestParams) {
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
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleEditUser($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleMyProfile($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
	public function handleNewUser($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
}

?>