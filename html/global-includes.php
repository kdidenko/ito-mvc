<?php
/**
 * This file is used to include all required classes which couldn't be yet included dynamicly.
 */
//TODO: needs reimplementation for dynamic class inclusion since each
//      class name change requires changes at this file!


    require_once 'global-settings.php';

	# set MVC libraries include path
	//set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\Work\www\ito-mvc\html');

	require_once 'com/ito-global/services/xml/XsltHandler.php';
    require_once 'com/ito-global/services/SessionService.php';
    require_once 'com/ito-global/services/StorageService.php';

	require_once 'com/ito-global/mvc/models/ModelAndView.php';
	require_once 'com/ito-global/mvc/services/ActionsMappingResolver.php';
	require_once 'com/ito-global/mvc/services/MVCService.php';
    require_once 'com/ito-global/mvc/services/TemplateEngine.php';

	require_once 'com/ito-global/services/messages/MessageService.php';
	require_once 'com/ito-global/services/messages/LocalizationFactory.php';
    require_once 'com/ito-global/services/http/HttpHelper.php';

    require_once 'com/ito-global/db/sql/mysql/SQLClient.php';


?>