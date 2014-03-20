<?php 

namespace Ozziest\Consozzy\System;

/**
 * Config
 *
 * This class was created for configuration. 
 * You can change all configuration values.
 *
 * @package     Ozziest
 * @subpackage  Consozzy
 * @category    System
 * @author      Özgür Adem Işıklı
 * @link        https://bitbucket.org/ozguradem/argedb/
 */
class Config
{

	/**
	* Configs
	*
	* @var array
	*/
	public static $configs;

	public function __construct()
	{
		self::$configs = (object) array(

				/*
				* Console Language Selection
				*/
				'language' => 'tr',

				/**
				* Colored texts
				*/
				'colorStatus' => true,

				/**
				* Prompt Color
				*/
				'promptColor' => 'green',

				/**
				* Prompt Sub Color
				*/
				'promptSubColor' => 'yellow',

				/**
				* User Message Status
				*
				*	0 - Disables All Messages
				* 	1 - Success Messages
				* 	2 - Informatin Messages
				*	3 - Warning & All Messageses
				*/
				'userMessageStatus' => 3,

				/**
				* User Error Message Status
				*/
				'userErrorMessageStatus' => true

			);
		
	}

	/**
	* Get
	*
	* Get all data or one config element
	* 
	* @param  string $key
	* @return array
	*/
	public static function get($key = false)
	{
		if ($key === false) {
			return self::$configs;
		} else if (isset(self::$configs->{$key})) {
			return self::$configs->{$key};
		} else {
			Loader::setError("Config item is not defined: $key");
			return false;
		}
	}

}


