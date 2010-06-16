<?php

require_once 'com/itoglobal/xml/XmlElement.php';

class MVCService {
	
	const actionXmlStr = "<action></action>";
	
	
	public static function optimizeMapping($mappingObj) {
		# check if there are roles restrictions in the mapping
		if (isset($mappingObj->role)) {			
			$role = SessionService::getRole();
			foreach ($mappingObj->role as $key => $value){
				if($role == (string) $value['type']){
					$mappingObj = self::constructMapping($mappingObj, $value);
				}
			}
		
		}
		return $mappingObj;	
	}
	
	
	public static function getController($mappingObj) {
		$mappingObj = self::optimizeMapping($mappingObj);
		# process the mapping data
		$className = (string) $mappingObj->controller ['class'];
		# use the classname to extract class path and type
		$tokens = split ( '\.', $className );
		$classPath = implode ( '/', $tokens ) . '.php';
		$classType = array_pop ( $tokens );
		
		include_once $classPath;
		return new $classType ();
	}
	
	private static function constructMapping($root = null, $node = null) {
		# create an empty <action> element
		$result = new XmlElement(self::actionXmlStr);
		# copy the attributes if an old action object was provided
		if($root != null){
			$result->copyAttributes($root);
		}	
		if($node != null){
			$result->copyChildren($node);
		}
		return $result; 
	}
}
?>