<?php

//TODO: modify RequestDispatcher - modify RequestDispatcher class for different types requests handling
//TODO: implement dispatchHttpRequest method


class RequestDispatcher {
	
	private static $instance = NULL;
	
	public function dispatchHttpRequest($env) {
		$result = null;
		# initialize the http helper object
		HttpHelper::init ( $env );
		# retreive request parameters
		$uri = HttpHelper::getActionName ();
		$map = HttpHelper::getMappingParam ();
		$map = $map == '' ? ActionsMappingResolver::DEFAULT_MAPPING_FILE : $map;
		# initialize the ActionsMappingResolver and retreive the action mapping model
		ActionsMappingResolver::init ( $map );
		$mappingObj = ActionsMappingResolver::getActionMapping ( $uri );
		if (! isset ( $mappingObj )) {
			error_log ( "Could not resolve the Action Mapping for request path: $uri \r Environment details: \r" . print_r ( $_SERVER, true ) );
			die ( $_SERVER ['HTTP_HOST'] . $uri . ' Not found on server' );
		}
		# get the conntroller object instance
		$controller = MVCService::getController ( ( string ) $mappingObj->controller ['class'] );
		# do handle action
		$methodName = isset ( $mappingObj->controller ['method'] ) ? ( string ) $mappingObj->controller ['method'] : BaseActionController::MVC_DEFAULT_METHOD;
		# run the controller method and return MVC model object
		$result = $controller->$methodName ( $mappingObj, $_REQUEST );
		$result->setTemplate ( $mappingObj->template );
		return $result;
	}
	
	public function dispatchActionRequest($action) {
	
	}
	
	private function __construct() {
	}
	
	public static function getInstance() {
		return (self::$instance === NULL) ? self::$instance = new self () : self::$instance;
	}
}

?>