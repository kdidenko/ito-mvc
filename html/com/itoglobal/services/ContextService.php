<?php

require_once 'com/itoglobal/services/StorageService.php';

class ContextService {
	
	public function createContext($alias, $domen) {
		
		//creating struct
		StorageService::createDirectory ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR );
		StorageService::createDirectory ( StorageService::IMAGES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::createDirectory ( StorageService::STYLES . StorageService::PATH_SEPARATOR . $alias );
		StorageService::createDirectory ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR );
		
		StorageService::createFile ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR . $domen . '-mapping.xml' );
		StorageService::createFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . 'template.xml' );
		StorageService::createFile ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $domen . StorageService::PATH_SEPARATOR . StorageService::INC . StorageService::PATH_SEPARATOR . 'index.html', "Hello Word, I am " . $domen );
	}
	
	public function deleteContext($dir) {
		
		StorageService::deleteDirectory ( StorageService::CONTEXT . StorageService::PATH_SEPARATOR . $dir . '.com' . '-mapping.xml' );	
		StorageService::deleteDirectory ( StorageService::IMAGES . StorageService::PATH_SEPARATOR . $dir );
		StorageService::deleteDirectory ( StorageService::STYLES . StorageService::PATH_SEPARATOR . $dir );
		StorageService::deleteDirectory ( StorageService::TEMPLATES . StorageService::PATH_SEPARATOR . $dir . '.com' );
		
		}

}

?>