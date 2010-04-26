<?php
/**
 * This file is used to include all required classes which couldn't be yet included dynamicly.
 */
//TODO: needs reimplementation for dynamic class inclusion since each
//      class name change requires changes at this file!
	

    require_once 'global-settings.php';
	
	# set MVC libraries include path
	//set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\Work\www\ito-mvc\html');

	require_once 'com/itoglobal/services/xml/XsltHandler.php';
	require_once 'com/itoglobal/services/http/RequestDispatcher.php';	
    require_once 'com/itoglobal/services/SessionService.php';
    require_once 'com/itoglobal/services/StorageService.php';
    require_once 'com/itoglobal/services/ContextService.php';
    require_once 'com/itoglobal/lcms/UsersService.php';

	require_once 'com/itoglobal/mvc/models/ModelAndView.php';
	require_once 'com/itoglobal/mvc/services/ActionsMappingResolver.php';
	require_once 'com/itoglobal/mvc/services/MVCService.php';
	
	//TODO: TemplateEngine is crappy and requires implementation!
    require_once 'com/itoglobal/mvc/services/TemplateEngine.php';

	require_once 'com/itoglobal/services/messages/MessageService.php';
	require_once 'com/itoglobal/services/messages/LocalizationFactory.php';
    require_once 'com/itoglobal/services/http/HttpHelper.php';

	require_once 'com/itoglobal/db/DBClientHandler.php';    
?>