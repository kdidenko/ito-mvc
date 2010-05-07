<?php

class MVCService {
	
	public static function getController($className) {
		// use classname to extract class path and type
		$tokens = split ( '\.', $className );
		$classPath = implode ( '/', $tokens ) . '.php';
		$classType = array_pop ( $tokens );
		
		include_once $classPath;
		return new $classType ();
	}
}
?>