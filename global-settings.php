<?php
	define('DEFAULT_SCRIPT', 'index.php');
	define('DOCUMENT_ROOT', rtrim(str_replace(DEFAULT_SCRIPT, '', $_SERVER['SCRIPT_FILENAME']), '/\\') . '/');
	
	define('STORAGE_PATH', DOCUMENT_ROOT . 'storage/');
	define('SESSION_PATH', DOCUMENT_ROOT . 'storage/sessions/');
	define('TEMPLATES_PATH', DOCUMENT_ROOT . 'templates/');	
	define('XSLT_PATH', DOCUMENT_ROOT . 'templates/xslt/');

	define('XML_CONTENT_DEFINITION', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r");

	# localization:
	define('DEFAULT_LOCALE', 'en');
	
	//TODO: setting below must be stored somewhere else and have to be dynamically loaded 
	//		by smth. like "SettingsService" which must be also implemented!
	$support = 'support@project.com';
	
	$db_host = 'localhost';
	$db_name = 'kdidenko_acclvivrealty';
	$db_user = 'kdidenko_script';		
	$db_pass = 'password';
	$charset = 'UTF8';
	
	#max size of file (in bytes)
	$upload_file_size = 512000;
	
	define('SUPPORT', $support);
	define('FILE_SIZE', $upload_file_size);
?>