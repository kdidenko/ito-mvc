<?php

require_once 'com/itoglobal/mvc/defaults/BaseActionControllerImpl.php';

class ContextService extends BaseActionControllerImpl {
	
	const ALIAS = 'alias';
	const DOMEN = 'dom';
	const DELETE = 'delete';
	
	public static $res;
	public static $view;
	
	public function CreateContext($actionParams, $requestParams){
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		$domen = $_POST[self::DOMEN];
		$alias = $_POST[self::ALIAS];
		//self::$res = StorageService::initSessionStorage($domen);
		
		$path = StorageService::getStoragePath($domen);
		$list = StorageService::readDir($path);
        foreach ($list as $next){
       		self::$view .= $next . "<br/>"; 	
        }
        
        
        
        file_exists('context/') ? '' : mkdir('context/', 0755, true) ;
        file_exists('images/' . $alias) ? '' : mkdir('images/' . $alias, 0755, true) ;
        file_exists('templates/' . $domen . '/inc') ? '' : mkdir('templates/' . $domen . '/inc', 0755, true) ;
		
        $file = 'context/' . $domen . '-mapping.xml';
	    if( !file_exists($file)){
	        $file = fopen($file, 'w') or die("can't open file");
			fclose($file);
        }
		
        $file = 'templates/' . $domen . '/template.xml';
		if( !file_exists($file)){
			$file = fopen($file, 'w') or die("can't open file");
			fclose($file);
		}
		
		$file = 'templates/' . $domen . '/inc/index.html';
		if( !file_exists($file)){
			$file = fopen($file, 'w') or die("can't open file");
	        fwrite($file, "Hello Word, I am " . $domen);
	        fclose($file);
		}
		return $mvc;
	} 
	
	
	
	public function DeleteContext($actionParams, $requestParams){
		// calling parent to get the model
		$mvc = $this->handleActionRequest ( $actionParams, $requestParams );
		
		
		$domen = $_POST[self::DELETE];
		self::$res = StorageService::clearSessionStorage($domen);
		return $mvc;
	} 
		




}


?>