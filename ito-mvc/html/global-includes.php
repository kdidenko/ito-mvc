<?php
	include_once 'global-settings.php';

	# set MVC libraries include path
	//set_include_path(get_include_path() . PATH_SEPARATOR . 'D:\Work\www\ito-mvc\html');

	include_once 'com/ito-global/services/xml/XsltHandler.php';
    include_once 'com/ito-global/services/SessionService.php';
    include_once 'com/ito-global/services/StorageService.php';

	include_once 'com/ito-global/mvc/models/ModelAndView.php';
	include_once 'com/ito-global/mvc/services/ActionsMappingResolver.php';
	include_once 'com/ito-global/mvc/services/MVCService.php';
    include_once 'com/ito-global/mvc/services/TemplateEngine.php';

	include_once 'com/ito-global/services/messages/MessageService.php';
	include_once 'com/ito-global/services/messages/LocalizationFactory.php';
    include_once 'com/ito-global/services/http/HttpHelper.php';
?>