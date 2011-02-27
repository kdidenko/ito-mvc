<?php
	define('DOCUMENT_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/');
	define('STORAGE_PATH', DOCUMENT_ROOT . 'storage/');
	define('SESSION_PATH', DOCUMENT_ROOT . 'storage/sessions/');
	define('TEMPLATES_PATH', DOCUMENT_ROOT . 'templates/');	
	define('XSLT_PATH', DOCUMENT_ROOT . 'templates/xslt/');

	define('XML_CONTENT_DEFINITION', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r");

	# localization:
	define('DEFAULT_LOCALE', 'en');
	
	//TODO: setting below must be stored somewhere else and have to be dynamically loaded 
	//		by smth. like "SettingsService" which must be also implemented!
	$support = 'astabryn@gmail.com'; 
	
	# db settings
	$db_host = 'localhost';
	$db_name = 'sound';		
	$db_user = 'root';		
	$db_pass = '';
	$charset = 'UTF8';
	
	# company logo size
	$height = 130;
	$width = 240;
	
	#display comments on page
	$on_page = 1;
	
	#max size of file (in bytes)
	$upload_file_size = 512000;
	
	define('COMPANY_LOGO_HEIGHT', $height);
	define('COMPANY_LOGO_WIDTH', $width);
	define('SUPPORT', $support);
	define('ON_PAGE', $on_page);
	define('FILE_SIZE', $upload_file_size);
?>