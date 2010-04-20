<?php
    //TODO: externalize all Strings inside the php code
    //TODO: do implement and use the Logger object for this:
	error_reporting(E_ALL);
    ini_set('error_log', 'error_log.txt');
	ini_set('error_reporting', E_ALL);
	ini_set('log_errors', TRUE);
    ini_set('html_errors', TRUE);
	ini_set('display_errors', TRUE);

    # include all required code
	include_once 'global-includes.php';

	# session may be started now
	//SessionService::startSession();

	# initialize the http helper object
	HttpHelper::init($_SERVER);

	$uri = HttpHelper::getActionName();
	$map = HttpHelper::getMappingParam();
    $cntxt = HttpHelper::getContextName();

    $map = $map == ''  ? ActionsMappingResolver::DEFAULT_MAPPING_FILE : $map;
	# initialize the ActionsMappingResolver and retreive the action mapping model
	ActionsMappingResolver::init($map, $cntxt);

	$mappingObj = ActionsMappingResolver::getActionMapping($uri);
	if( !isset($mappingObj) ){
		error_log("Could not resolve the Action Mapping for request path: $uri \r Environment details: \r" .
				print_r($_SERVER, true));
		die($_SERVER['HTTP_HOST'] . $uri . ' Not found on server');
	}

    # get the conntroller object instance
	$controller = MVCService::getController((string) $mappingObj->controller['class']);

	# do handle action
	$methodName = isset($mappingObj->controller['method']) ?
	                  (string) $mappingObj->controller['method'] :
	                          BaseActionController::MVC_DEFAULT_METHOD;

	# run the controller method and get an MVC model object
	$mvc = $controller->$methodName($mappingObj, $_REQUEST);
	$mvc->setContext($cntxt);

	# initialize the Messages Service
	$messages = MessageService::getInstance();
	$messages->loadMessages(DEFAULT_LOCALE);

	# get the template configuration
	$template = $mappingObj->template;

    //TODO: use "Trigger Registration" mechanism instead of implicitly specifying functions names at
    //      output buffering initialization.
    # start output buffering with registered i18n for an output postprocessing
    ob_start('_i18n');
        # go! go! go!
	    TemplateEngine::run($template);
	# show it up!
    @ob_end_flush();
?>