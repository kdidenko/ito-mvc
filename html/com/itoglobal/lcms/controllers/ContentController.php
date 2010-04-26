<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class ContentController extends SecureActionControllerImpl {
	
	const RESULT = 'result';
	
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
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams ); //print_r($_POST);exit;
		$result = UsersService::getField ();
		isset ( $result ) ? $mvc->addObject ( self::RESULT, $result ) : null;
		
		/*$firstname = SessionService::getAttribute ( UsersService::FIRSTNAME );
		isset ( $firstname ) ? $mvc->addObject ( UsersService::FIRSTNAME, $firstname ) : null;
		//isset ( $result [0] [UsersService::ID] ) ? $mvc->addObject ( UsersService::ID, $result [0] [UsersService::ID] ) : null;
		isset ( $result [0] [UsersService::USERNAME] ) ? $mvc->addObject ( UsersService::USERNAME, $result [0] [UsersService::USERNAME] ) : null;
		isset ( $result [0] [UsersService::LASTNAME] ) ? $mvc->addObject ( UsersService::LASTNAME, $result [0] [UsersService::LASTNAME] ) : null;
		//isset ( $result [0] [UsersService::EMAIL] ) ? $mvc->addObject ( UsersService::EMAIL, $result [0] [UsersService::EMAIL] ) : null;
		//isset ( $result [0] [UsersService::ENABLED] ) ? $mvc->addObject ( UsersService::ENABLED, $result [0] [UsersService::ENABLED] ) : null;
		//isset ( $result [0] [UsersService::DELETED] ) ? $mvc->addObject ( UsersService::DELETED, $result [0] [UsersService::DELETED] ) : null;
		isset ( $result [0] [UsersService::ROLE] ) ? $mvc->addObject ( UsersService::ROLE, $result [0] [UsersService::ROLE] ) : null;
		*/	
		
		return $mvc;
	}
}

?>