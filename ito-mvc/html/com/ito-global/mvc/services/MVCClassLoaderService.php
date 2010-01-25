<?php

class MVCClassLoaderService {

    public static function getInstanceByClassName($className){
        // use classname to extract class path and type
        $tokens = split('\.', $className);
        $classPath = implode('/', $tokens) . '.php';
        $classType = array_pop($tokens);

    	// define controller constants
        define('CONTROLLER_PATH', $classPath);
 	    include_once CONTROLLER_PATH;

	    return new $classType;
    }
}
?>