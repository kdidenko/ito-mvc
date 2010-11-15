<?php

require_once 'com/itoglobal/mvc/defaults/SecureActionControllerImpl.php';

class SubHeaderController extends SecureActionControllerImpl {

	public function handleHome($actionParams, $requestParams) {
		return $this->handleActionRequest ( $actionParams, $requestParams );
	}
}
?>