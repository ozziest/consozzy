<?php

/**
* Checking environment
*/
if (php_sapi_name() != 'cli') {
	exit('No direct script access allowed');
}

define('CONSOLE', TRUE);
define('KERNEL', 'system');

/**
* Checking console core
*/
if (!file_exists(KERNEL.'/config.php') || !file_exists(KERNEL.'/kernel.php')) {
	die("Kernel is not found. \n");
}

/**
* Load kernel files
*/
require_once(KERNEL.'/kernel.php');

// Initializing Kernel
$kernel = new Kernel();	
$kernel->_init();

?>