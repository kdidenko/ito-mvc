<?php
	define('DOCUMENT_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/');
	define('STORAGE_PATH', DOCUMENT_ROOT . 'storage/');
	define('DATA_PATH',  DOCUMENT_ROOT . 'data/');
	define('SESSION_PATH', DOCUMENT_ROOT . 'storage/sessions/');
	define('TEMPLATES_PATH', DOCUMENT_ROOT . 'templates/');	
	define('XSLT_PATH', DOCUMENT_ROOT . 'templates/xslt/');
	define('CONTEXT_PATH', 'http://' . $_SERVER['HTTP_HOST'] . '/');	

	define('XML_CONTENT_DEFINITION', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r");

	// localization:
	define('DEFAULT_LOCALE', 'uk');
	
	//TODO: setting below must be stored somewhere else and have to be dynamically loaded 
	//		by smth. like "SettingsService" which must be also implemented!
	// db settings
	$db_host = 'localhost';
	$db_name = 'lr';		
	$db_user = 'root';		
	$db_pass = '';
	$charset = 'UTF8';
	
	$_LOCALES = array('uk', 'en', 'ru', 'de', 'it'); 
?>