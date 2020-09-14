<?php

$sysstart = microtime(true);
function_exists('memory_get_usage') ? define('MEM_USAGE', memory_get_usage()) : null;
// Version
define('VERSION', '3.0.2.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}


//require_once(DIR_SYSTEM . 'library/debug.php'); 
// Startup
require_once(DIR_SYSTEM . 'startup.php');


start('catalog',$sysstart);


