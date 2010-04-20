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

	public function CreateContext($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$domen = $requestParams [self::DOMAIN];
		$alias = $requestParams [self::ALIAS];
		
		
		//creating struct
		StorageService::CreateDirectory ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR );
		StorageService::CreateDirectory ( StorageService::IMAGES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::CreateDirectory ( StorageService::STYLES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::CreateDirectory ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR );
		
		StorageService::CreateFile ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR . $domen . '-mapping.xml' );
		StorageService::CreateFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . 'template.xml' );
		StorageService::CreateFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR . 'index.html', "Hello Word, I am " . $domen );
		
		return $mvc;
	}
	
	public function DeleteContext($actionParams, $requestParams) {
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$dir = $_POST [self::DELETE];
		StorageService::DeleteDirectory ( $dir );
		
		return $mvc;
	}

}

?>