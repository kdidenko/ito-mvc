<?php
	define('DOCUMENT_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/');
	define('STORAGE_PATH', DOCUMENT_ROOT . 'storage/');
	define('SESSION_PATH', DOCUMENT_ROOT . 'storage/sessions/');
	define('MOCKUPS',  DOCUMENT_ROOT . 'storage/mockup/');
	define('ORDERS',  DOCUMENT_ROOT . 'storage/orders/');
	define('ADS_PATH',  DOCUMENT_ROOT . 'storage/ads/');
	define('XSLT_PATH', DOCUMENT_ROOT . 'templates/xslt/');

	// IMAGE_SRC_MODE - defines the scan source; 0: use scanner 1: use mockup
	define('IMAGE_SRC_MODE', 0);
    define('TWAIN_CMD_STR', DOCUMENT_ROOT . 'twaincom.exe {$TARGET} -h -o -progressionoff -truecolor -n -paperformatphoto13x9 -r 200 -s -jpegquality 100');

	define('XML_CONTENT_DEFINITION', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r");

	/* Google API constants section
	 *  MAPS_API_KEY - the key required for Using Google Maps within application.
	 *  				This API keys are assigned per domain name. Each new domain
	 *  				will require new registration.
	 *  DOCS_USER	 - Google Docs account username used for OCR features.
	 *  DOCS_PASS	 - Google Docs account password
	 */

	# ito-global.com:
	//define('MAPS_API_KEY', 'ABQIAAAAYi5Sw4Aj51nLRkBwjqRm2BR3EAJjBUScgu6e3IhbI6pTz17b4RQ3T3VGwwEfOEFvYXnXl5pL0aB0xw');
	# localhost:
	define('MAPS_API_KEY', 'ABQIAAAAYi5Sw4Aj51nLRkBwjqRm2BQnBkPqi3cRTwxcW_XHCiwsd1zwgRRzhT00KK270OCKycqrneylequPRw');

	define('DOCS_USER', 'rezeptomat@gmail.com');
	define('DOCS_PASS', 'R3z3pt0m4t');

	// localization:
	define('DEFAULT_LOCALE', 'de');
?>