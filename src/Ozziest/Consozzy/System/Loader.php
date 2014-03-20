<?php 

namespace Ozziest\Consozzy\System;

/**
 * Loader
 *
 * This class loads console application
 *
 * @package     Ozziest
 * @subpackage  Consozzy
 * @category    System
 * @author      Özgür Adem Işıklı
 * @link        https://bitbucket.org/ozguradem/argedb/
 */
class Loader
{

	/**
	* Error Message
	*
	* @var string
	*/
	private static $errorMessage = false;

	/**
	* Kernel Load Status
	*
	* @var boolean
	*/
	private static $loaded = false;

	/**
	* Config Class Object
	*
	* @var object
	*/
	public static $config;

	/**
	* Language Class Object
	*
	* @var object
	*/
	public static $language;

	/**
	* Kernel Class Object
	*/
	public static $kernel;

	/**
	* Construct
	*/
	public function __construct()
	{

        /**
        * Checking CLI application
        */
		if (php_sapi_name() != 'cli') {
			$this->setError("No direct script access allowed.");
		}

		/**
		* Setting error handler
		*/
		set_error_handler(array($this, 'errorHandler'));

		// Loading all elements
		self::$config = new Config();
		self::$language = new Language();
		if (Config::get('colorStatus')) {
			new Colors();
		}
		self::$kernel = new Kernel();
		self::$loaded = true;
		self::$kernel->_init();

		//new Kernel();

		/*
		* Checking error message status. If we have got 
		* an error then $errorMessage variable is not false.
		*/
		if (self::$errorMessage !== false ) {
			exit(self::$errorMessage);
		}

		echo "Console is closed! \n";

	}

	/**
	* Error Handler
	* 
	* Error handler method. All errors sending main error method.
	* This method is just an alias.
	*
	* @param  string $no
	* @param  string $str
	* @param  string $file
	* @param  string $line
	* @return null
	*/
	public static function errorHandler($no = 0, $str, $file = 0, $line = 0)
	{
		if (self::$loaded == false) {
			self::setError($str);
		} else {
			self::$kernel->error("$no: $str $file, $line ");
		}
	}

	/**
	* Set Error
	*
	* @param  string $message
	* @return null
	*/
	public function setError($message)
	{
		if (self::$errorMessage === false) {
			self::$errorMessage = $message." \n";			
		}
	}

}
