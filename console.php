<?php

/**
* Checking environment
*/
if (php_sapi_name() != 'cli') {
	exit('No direct script access allowed');
}

/**
* Checking console core
*/
if (!file_exists('kernel/config.php') || !file_exists('kernel/kernel.php')) {
	die("Kernel is not found. \n");
}

/**
* Load kernel files
*/
define('CONSOLE', TRUE);
require_once('kernel/kernel.php');

// Initializing Kernel
$kernel = new Kernel();	

?>