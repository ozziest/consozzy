<?php 

namespace Ozziest\Consozzy\System;

/**
 * Language
 *
 * Language Management
 *
 * @package     AHIR
 * @subpackage  Argedb
 * @category    Library
 * @author      Özgür Adem Işıklı
 * @link        https://bitbucket.org/ozguradem/argedb/
 */
class Language
{

	/**
	* Files will be installed automatically.
	*
	* @var array
	*/
	public $autoLoads = array(
		'console'
		);

	/**
	* Loaded 
	*
	* @var array
	*/
	public static $loaded = array(
		);

	/**
	* Loaded Lang Values
	*
	* @var array
	*/
	public static $lang = array();

	/**
	* langCode
	*
	* @var boolean
	*/
	public static $langCode = false;

	/**
	* Construct
	*
	* Load for autoloading
	*/
	public function __construct()
	{	
		/*
		* Setting Lang Code
		*/
		$langCode = Config::get('language');
		if ($langCode !== false) {
			self::$langCode = $langCode;
			$this->autoLoad();
		}
	}

	/**
	* Auto Load
	*
	* Load auto load files
	*
	* @return null
	*/
	public function autoLoad()
	{

		/*
		* Checking general language directory
		*/
		$directory = __DIR__.'/../Language/';
		if (!file_exists($directory)) {
			Loader::setError('Language directory is not found.');
			return;
		}

		/*
		* Checking special language directory
		*/
		$directory .= self::$langCode.'/';
		if (!file_exists($directory)) {
			Loader::setError("Language selection folder is not found: `".self::$langCode."");
			return;
		}

		/*
		* Loading language files
		*/
		foreach ($this->autoLoads as $fileName) {
			if (!file_exists($directory.$fileName.'.php')) {
				return Loader::setError("Language file is missing: $fileName");
			}
			$lang = '';
			require_once($directory.$fileName.'.php');
			array_push(self::$loaded, $fileName);
			$this->loadKeys($lang);
		}

	}

	/**
	* Load
	*
	* Language file loading.
	* 
	* @param  string $fileName
	* @return boolean
	*/
	public static function load($fileName)
	{	
		// Check loaded before?
		if (in_array($fileName, self::$loaded)) {
			return true;
		}
		// Set language directory
		$directory = __DIR__.'/../Language/'.self::$langCode.'/';
		if (!file_exists($directory.$fileName.'.php')) {
			return Loader::errorHandler(null, "Language file is missing: `$fileName.php` ");
		}
		// Load lang file
		$lang = '';
		array_push(self::$loaded, $fileName);
		require_once($directory.$fileName.'.php');
		// Set values
		self::loadKeys($lang);
		return true;
	}

	/**
	* Get
	*
	* @param  string $key
	* @return string
	*/
	public static function get($key)
	{
		if (isset(self::$lang[$key])) {
			return self::$lang[$key];
		} else {
			return false;
		}
	}

	/**
	* Load Şanguage Keys
	*
	* @param  array $keys
	* @return null
	*/
	public static function loadKeys($keys)
	{
		if (is_array($keys)) {
			foreach ($keys as $key => $value) {
				self::$lang[$key] = $value;
			}			
		}
	}

}