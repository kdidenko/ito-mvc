<?php
	define('DOCUMENT_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/');
	define('STORAGE_PATH', DOCUMENT_ROOT . 'storage/');
	define('SESSION_PATH', DOCUMENT_ROOT . 'storage/sessions/');
	define('XSLT_PATH', DOCUMENT_ROOT . 'templates/xslt/');

	define('XML_CONTENT_DEFINITION', "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r");

	// localization:
	define('DEFAULT_LOCALE', 'en');
	
	// db settings
	$db_host = 'localhost';
	$db_name = 'ito_trainings';		
	$db_user = 'ito';		
	$db_pass = 'Ssn123456';
?>