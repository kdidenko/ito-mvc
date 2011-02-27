<?php
/**
 * This file is used to include all required classes which couldn't be yet included dynamicly.
 */
//TODO: needs reimplementation for dynamic class inclusion since each
//      class name change requires changes at this file!
	
    require_once 'global-settings.php';
	//TODO: need to refactoring
	require_once 'aliases.php';
	# set MVC libraries include path
	//set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\Work\www\ito-mvc\html');

	require_once 'com/itoglobal/xml/XsltHandler.php';
	require_once 'com/itoglobal/http/RequestDispatcher.php';	
    require_once 'com/itoglobal/session/SessionService.php';
    require_once 'com/itoglobal/storage/StorageService.php';
    require_once 'com/itoglobal/storage/ImageService.php';
	require_once 'com/itoglobal/mail/MailerService.php';
	require_once 'com/itoglobal/validation/ValidationService.php';
	
	require_once 'com/itoglobal/sound/services/UploadsService.php';
	require_once 'com/itoglobal/sound/services/CompanyService.php';
	require_once 'com/itoglobal/sound/services/UsersService.php';
    require_once 'com/itoglobal/sound/services/MailService.php';
    require_once 'com/itoglobal/sound/services/CategoryService.php';
    require_once 'com/itoglobal/sound/services/SubCategoryService.php';
    require_once 'com/itoglobal/sound/services/StaticBlockService.php';
    require_once 'com/itoglobal/sound/services/PlanService.php';
    require_once 'com/itoglobal/sound/services/RemindService.php';
    require_once 'com/itoglobal/sound/services/RegionService.php';
    require_once 'com/itoglobal/sound/services/CountryService.php';
    require_once 'com/itoglobal/sound/services/BargainsService.php';
    require_once 'com/itoglobal/sound/services/OrdersService.php';
    
    
    
    
	require_once 'com/itoglobal/mvc/models/ModelAndView.php';
	require_once 'com/itoglobal/mvc/core/ActionsMappingResolver.php';
	require_once 'com/itoglobal/mvc/core/MVCService.php';
	
	//TODO: TemplateEngine is crappy and requires implementation!
    require_once 'com/itoglobal/mvc/core/TemplateEngine.php';

	require_once 'com/itoglobal/i18n/MessageService.php';
	require_once 'com/itoglobal/i18n/LocalizationFactory.php';
    require_once 'com/itoglobal/http/HttpHelper.php';

	require_once 'com/itoglobal/db/DBClientHandler.php';    
?>