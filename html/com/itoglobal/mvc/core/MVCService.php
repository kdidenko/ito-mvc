<?php

require_once 'com/itoglobal/xml/XmlElement.php';

class MVCService {
	
	const ACTION_XML_STR = "<action></action>";
	
	public static function optimizeMapping($mappingObj) {
		# check if there are roles restrictions in the mapping
		if (isset ( $mappingObj->role )) {
			$role = SessionService::getRole ();
			foreach ( $mappingObj->role as $key => $value ) {
				if ($role == ( string ) $value ['type']) {
					$mappingObj = self::constructMapping ( $mappingObj, $value );
				}
			}
		
		}
		return $mappingObj;
	}
	
	public static function getController($mappingObj) {
		$mappingObj = self::optimizeMapping ( $mappingObj );
		# process the mapping data
		$className = ( string ) $mappingObj->controller ['class'];
		# use the classname to extract class path and type
		$tokens = explode ( '.', $className );
		$classPath = implode ( '/', $tokens ) . '.php';
		$classType = array_pop ( $tokens );
		
		include_once $classPath;
		return new $classType ();
	}
	
	public static function getForwards($mappingObj, $mvc) {
		//TODO: quick fix - must be implemented a better way like adding validation if forwards already exist at $mappingObj
		if ($mvc && ($model = $mvc->getModel ())) {
			if (isset ( $model ['action_forwards'] )) {
				$mappingObj = new XmlElement ( $mappingObj->asXML () );
				$forwards = $mappingObj->addChild ( 'forwards' );
				$forwards->copyChildren ( $model ['action_forwards'] );
			}
		}
		return $mappingObj;
	}
	
	private static function constructMapping($root = null, $node = null) {
		# create an empty <action> element
		$result = new XmlElement ( self::ACTION_XML_STR );
		# copy the attributes if an old action object was provided
		if ($root != null) {
			$result->copyAttributes ( $root );
		}
		if ($node != null) {
			$result->copyChildren ( $node );
		}
		return $result;
	}
	
	public static function margeModels($mvc1, $mvc2) {
		$keys = $mvc2->getKeyset ();
		foreach ( $keys as $name ) {
			if ($mvc1->objectExists ( $name )) {
				$obj = self::margeObjects ( $name, $mvc1->getObject ( $name ), $mvc2->getObject ( $name ) );
				$mvc1->addObject ( $name, $obj );
			} else {
				$mvc1->addObject ( $name, $mvc2->getObject ( $name ) );
			}
		}
		return $mvc1;
	}
	
	private static function margeObjects($type, $obj1, $obj2) {
		$result = $obj1 && is_object ( $obj1 ) && $obj1->asXML () ? new XmlElement ( $obj1->asXML () ) : null;
		switch ($type) {
			case 'action_properties' :
				$result = $obj1; //TODO: temporary stub
				break;
			case 'request_params' :
				$result = $obj1; //TODO: temporary stub
				break;
			case 'action_forwards' :
				if ($obj2->target) {
					foreach ( $obj2->target as $target2 ) {
						$cond2 = ( string ) $target2 ['condition'];
						$exists = false;
						if ($obj1->target) {
							foreach ( $result->target as $target1 ) {
								$cond1 = ( string ) $target1 ['condition'];
								if ($cond2 == $cond1) {
									$exists = true;
									break;
								}
							}
						} else if (! $obj1->asXML ()) {
							return $obj2;
						}
						if (! $exists) {
							$obj1->copyChildren ( $target2 );
						}
					}
				}
				$result = $obj1;
				break;
			default :
				$result = $obj1;
				break;
		}
		return $result;
	}

}
?>