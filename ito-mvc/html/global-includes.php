<?php
	include_once 'global-settings.php';
	include_once 'aliases.php';

	# append third-party libraries include path
	set_include_path(get_include_path() . PATH_SEPARATOR . 'lib');

	include_once 'com/ito-global/services/xml/XsltHandler.php';

	include_once 'com/ito-global/mvc/models/ModelAndView.php';
	include_once 'com/ito-global/mvc/services/ActionsMappingResolver.php';
	include_once 'com/ito-global/mvc/services/MVCClassLoaderService.php';

	include_once 'com/ito-global/services/messages/MessageService.php';
	include_once 'com/ito-global/services/messages/LocalizationFactory.php';
    include_once 'com/ito-global/services/http/HttpHelper.php';

	include_once 'com/ito/expopharm/services/SessionService.php';
	include_once 'com/ito/expopharm/services/StorageService.php';
	include_once 'com/ito/expopharm/services/ScanningService.php';
	include_once 'com/ito/expopharm/services/OrderService.php';
	include_once 'com/ito/expopharm/services/AdsService.php';

	include_once 'com/google/doclist/ocr/GoogleOcrClient.php';

?>