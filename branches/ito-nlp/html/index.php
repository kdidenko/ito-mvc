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

	# get Request Dispatcher
	$rd = RequestDispatcher::getInstance();
	$mvc = $rd->dispatchHttpRequest($_SERVER);
	
	# initialize the Messages Service
	//TODO: temporary disabled
	//$messages = MessageService::getInstance();
	//$messages->loadMessages(DEFAULT_LOCALE);

    //TODO: use "Trigger Registration" mechanism instead of implicitly specifying functions names at
    //      output buffering initialization.
    # start output buffering with registered i18n for an output postprocessing
    ob_start('_i18n');
        # go! go! go!
	    TemplateEngine::run($mvc);
	# show it up!
    @ob_end_flush();
?>