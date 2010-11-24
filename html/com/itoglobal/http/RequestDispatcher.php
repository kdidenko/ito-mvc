<?php

class RequestDispatcher {
	
	private static $instance = NULL;
	
	public function dispatchHttpRequest($env) {
		# initialize the http helper object
		HttpHelper::init ( $env );
		# retreive request parameters
		$action = HttpHelper::getActionName ();
		# return dispatched action result
		return $this->dispatchActionRequest ( $action );
	}
	
	public function dispatchActionRequest($action) {
		$map = $this->getMappingConfig ();
		# initialize the ActionsMappingResolver and retreive the action mapping model
		ActionsMappingResolver::init ( $map );
		$mappingObj = ActionsMappingResolver::getActionMapping ( $action );
		if (! isset ( $mappingObj )) {
			error_log ( "Could not resolve the Action Mapping for request path: $action \r Environment details: \r" . print_r ( $_SERVER, true ) );
			die ( $action . ' Not found on server' );
		}
		return $this->dispatch ($mappingObj);
	}
	
	public function dispatchViewRequest($view) {
		$map = $this->getMappingConfig ();
		# initialize the ActionsMappingResolver and retreive the action mapping model
		ActionsMappingResolver::init ( $map );
		$mappingObj = ActionsMappingResolver::getViewMapping ($view);
		if (! isset ( $mappingObj )) {
			error_log ( "Could not resolve the Action Mapping for request path: $view \r Environment details: \r" . print_r ( $_SERVER, true ) );
			die ( $view . ' Not found on server' );
		}
		return $this->dispatch ( $mappingObj );
	}
	
	private function dispatch($mappingObj) {
		$result = null;
		# get the conntroller object instance
		$mappingObj = MVCService::optimizeMapping($mappingObj);
		$controller = MVCService::getController ($mappingObj);
		# do handle action
		$methodName = isset ( $mappingObj->controller ['method'] ) ? ( string ) $mappingObj->controller ['method'] : BaseActionController::MVC_DEFAULT_METHOD;
		$methodName = method_exists($controller, $methodName) ? $methodName : BaseActionController::MVC_DEFAULT_METHOD;
		# run the controller method and return MVC model object
		$result = $controller->$methodName ($mappingObj, $_REQUEST);
		return $result;
	}
	
	private function __construct() {
	}
	
	public static function getInstance() {
		return (self::$instance === NULL) ? self::$instance = new self () : self::$instance;
	}
	
	private function getMappingConfig() {
		$map = HttpHelper::getMappingParam ();
		return $map == '' ? ActionsMappingResolver::DEFAULT_MAPPING_FILE : $map;
	}
	
	private function getTemplateConfig($mappingObj) {
		return $mappingObj->template;
	}
}

?>