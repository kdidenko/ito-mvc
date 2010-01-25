<?php
    //TODO: do implement and use the Logger object for this:
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	ini_set('log_errors', TRUE);
    ini_set('html_errors', TRUE);
	ini_set('display_errors', TRUE);

    // include all required code
	include_once 'global-includes.php';
	// session may be started now
	SessionService::startSession();
	// initialize the http helper object
	HttpHelper::init($_SERVER);

	$uri = HttpHelper::getActionName();
	$map = HttpHelper::getMappingParam();
    $cntxt = HttpHelper::getContextName();

    $map = $map == ''  ? ActionsMappingResolver::DEFAULT_MAPPING_FILE : $map;
	// init the ActionsMappingResolver and retreive the action mapping model
	ActionsMappingResolver::init($map, $cntxt);

	$mappingObj = ActionsMappingResolver::getActionMapping($uri);
	if( !isset($mappingObj) ){
		error_log("Could not resolve the Action Mapping for request path: $uri \r Environment details: \r" .
				print_r($_SERVER, true));
		die($_SERVER['HTTP_HOST'] . $uri . ' Not found on server');
	}

	//TODO: rename this class and method - sound too complicated
    // create the conntroller object instance
	$controller = MVCClassLoaderService::getInstanceByClassName((string) $mappingObj->controller['class']);

	// do handle action
	$methodName = isset($mappingObj->controller['method']) ?
	                  (string) $mappingObj->controller['method'] :
	                          BaseActionController::MVC_DEFAULT_METHOD;

	$mvc = $controller->$methodName($mappingObj, $_REQUEST);
	$mvc->setContext($cntxt);

	// initialize the Messages Service
	$messages = MessageService::getInstance();
	$messages->loadMessages(DEFAULT_LOCALE);

	// define page template constants
    define('TEMPLATE_PATH', (string) $mappingObj->template['path']);

    // start output buffering with registered i18n for an output postprocessing
    ob_start('_i18n');
        // go! go! go!
	    include_once TEMPLATE_PATH;
	// show it up!
    ob_end_flush();

?>