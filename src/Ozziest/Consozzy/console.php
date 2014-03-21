<?php
	
	/**
	* Autoloader 
	*/
	require_once __DIR__.'/../../../vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
	$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
	$loader->registerNamespaces(array('Ozziest' => __DIR__.'/../../'));
	$loader->register();
	if (PHPUNIT !== true) {
		new Ozziest\Consozzy\System\Loader();				
	}
