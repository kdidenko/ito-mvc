<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class ContextService extends BaseActionControllerImpl {
	
	const ALIAS = 'alias';
	const DOMEN = 'dom';
	const DELETE = 'delete';
	const CONTEXT = 'context';
	const IMAGES = 'images';
	const TEMPLATES = 'templates';
	const STYLES = 'styles';
	const INC = 'inc';
	const PATH_SEPARATOR = '/'; 
	
	public static $res;
	public static $view;
	
	public function CreateContext($actionParams, $requestParams){
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$domen = $_POST[self::DOMEN];
		$alias = $_POST[self::ALIAS];
		//self::$res = StorageService::initSessionStorage($domen);
		
		/*$path = StorageService::getStoragePath($domen);
		$list = StorageService::readDir($path);
        foreach ($list as $next){
       		self::$view .= $next . "<br/>"; 	
        }*/
        
        
        //creating struct
        self::CreateDirectory(self::CONTEXT . self::PATH_SEPARATOR);
        self::CreateDirectory(self::IMAGES . self::PATH_SEPARATOR . $alias);
        self::CreateDirectory(self::STYLES . self::PATH_SEPARATOR . $alias);
        self::CreateDirectory(self::TEMPLATES . self::PATH_SEPARATOR . $domen . self::PATH_SEPARATOR . self::INC . self::PATH_SEPARATOR);
		
        self::CreateFile(self::CONTEXT . self::PATH_SEPARATOR . $domen . '-mapping.xml');
	    self::CreateFile(self::TEMPLATES . self::PATH_SEPARATOR . $domen . self::PATH_SEPARATOR . 'template.xml');
		self::CreateFile(self::TEMPLATES . self::PATH_SEPARATOR . $domen . self::PATH_SEPARATOR . self::INC . self::PATH_SEPARATOR . 'index.html',  "Hello Word, I am " . $domen);
		
		return $mvc;
	} 
	
	public function DeleteContext($actionParams, $requestParams){
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$dir = $_POST[self::DELETE];
		self::DeleteDirectory($dir);
		
		return $mvc;
	} 
	
	private function CreateDirectory($path){
		file_exists($path) ? '' : mkdir($path, 0755, true) ;
	} 
	
	private function CreateFile($path, $context = null) {
		if( !file_exists($path)){
	        $path = fopen($path, 'w') or die("can't open file");
			isset($context) ? fwrite($path, $context) : fwrite($path, $context);
	        fclose($path);
        }
	}
		
	private function DeleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::deleteDirectory($dir . self::PATH_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    }
	


}


?>