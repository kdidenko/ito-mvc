<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class TrainingsController extends SecureActionControllerImpl {
	
	public function handleNew($actionParams, $requestParams) {
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		//TODO: just an empty stub
		return $mvc;
	}

}

?>