<?php
/**
 * This file is used to include all required classes which couldn't be yet included dynamicly.
 */
//TODO: needs reimplementation for dynamic class inclusion since each
//      class name change requires changes at this file!
	
    require_once 'global-settings.php';
    require_once 'post-processing.php';
	
	require_once 'com/itoglobal/xml/XsltHandler.php';
	require_once 'com/itoglobal/http/RequestDispatcher.php';	
    require_once 'com/itoglobal/session/SessionService.php';
    require_once 'com/itoglobal/storage/StorageService.php';
	require_once 'com/itoglobal/mail/MailerService.php';
	require_once 'com/itoglobal/validation/ValidationService.php';
	
    //require_once 'com/itoglobal/lcms/UsersService.php';
    
	require_once 'com/itoglobal/mvc/models/ModelAndView.php';
	require_once 'com/itoglobal/mvc/core/ActionsMappingResolver.php';
	require_once 'com/itoglobal/mvc/core/MVCService.php';
	
	//TODO: TemplateEngine is crappy and requires implementation!
    require_once 'com/itoglobal/mvc/core/TemplateEngine.php';

	require_once 'com/itoglobal/i18n/MessageService.php';
	require_once 'com/itoglobal/i18n/LocalizationFactory.php';
    require_once 'com/itoglobal/http/HttpHelper.php';

	require_once 'com/itoglobal/db/DBClientHandler.php';

	require_once 'com/mls/services/ListingService.php';
	require_once 'com/mls/services/NewsService.php';
?>