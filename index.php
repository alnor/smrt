<?php
	
	define("SMRT_CORE_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR."core");
	define("SMRT_APP_PATH", dirname(__FILE__).DIRECTORY_SEPARATOR."app");
	define("SMRT_DOCUMENT_ROOT", dirname(__FILE__));
	
	ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.SMRT_CORE_PATH.PATH_SEPARATOR.SMRT_APP_PATH.PATH_SEPARATOR.SMRT_DOCUMENT_ROOT);
	
	require_once SMRT_CORE_PATH.'/Smrt_FrontController.php';
	
	$controller = Smrt_FrontController::getInstance();
	$controller->init();
	$controller->dispatch();
	
?>