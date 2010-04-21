<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class ScopeController extends BaseActionControllerImpl {
	/**
	 * @var string - alias for new web site
	 */
	const ALIAS = 'alias';
	/**
	 * @var string - domain for new web site
	 */
	const DOMAIN = 'dom';
	/**
	 * @var string - domain which will be deleted
	 */
	const DELETE = 'delete';
	
	public function createScope($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$domen = $requestParams [self::DOMAIN];
		$alias = $requestParams [self::ALIAS];
		
		//creating struct
		ContextService::createContext( $alias, $domen );
		
		return $mvc;
	}
	
	public function deleteScope($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$dir = $_POST [self::DELETE];
		ContextService::deleteContext ( $dir );
		
		return $mvc;
	}

}

?>