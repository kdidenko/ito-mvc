<?php

require_once 'com/itoglobal/services/StorageService.php';
require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class ContextService extends BaseActionControllerImpl {
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

	public function createContext($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$domen = $requestParams [self::DOMAIN];
		$alias = $requestParams [self::ALIAS];
		
		
		//creating struct
		StorageService::createDirectory ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR );
		StorageService::createDirectory ( StorageService::IMAGES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::createDirectory ( StorageService::STYLES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::createDirectory ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR );
		
		StorageService::createFile ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR . $domen . '-mapping.xml' );
		StorageService::createFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . 'template.xml' );
		StorageService::createFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR . 'index.html', "Hello Word, I am " . $domen );
		
		return $mvc;
	}
	
	public function deleteContext($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$dir = $_POST [self::DELETE];
		StorageService::deleteDirectory ( $dir );
		
		return $mvc;
	}

}

?>