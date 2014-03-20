<?php 

namespace Ozziest\Consozzy\System;

/**
 * Kernel
 *
 * @package     ozguradem
 * @subpackage  Consozzy
 * @category    Kernel
 * @author      Ozgur Adem Isikli
 * @link        https://github.com/ozguradem/consozzy
 */
class Colors {

	/**
	* Foreground Colors
	* 
	* @var array
	*/
	private static $foregroundColors = array();
	
	/**
	* Construct method 
	*/
	public function __construct() 
	{
		// Set up shell colors
		self::$foregroundColors['black'] = '0;30';
		self::$foregroundColors['dark_gray'] = '1;30';
		self::$foregroundColors['blue'] = '0;34';
		self::$foregroundColors['light_blue'] = '1;34';
		self::$foregroundColors['green'] = '0;32';
		self::$foregroundColors['light_green'] = '1;32';
		self::$foregroundColors['cyan'] = '0;36';
		self::$foregroundColors['light_cyan'] = '1;36';
		self::$foregroundColors['red'] = '0;31';
		self::$foregroundColors['light_red'] = '1;31';
		self::$foregroundColors['purple'] = '0;35';
		self::$foregroundColors['light_purple'] = '1;35';
		self::$foregroundColors['brown'] = '0;33';
		self::$foregroundColors['yellow'] = '1;33';
		self::$foregroundColors['light_gray'] = '0;37';
		self::$foregroundColors['white'] = '1;37';
	}
 	
	/**
	* Get
	*
	* @param  string $string
	* @param  string $foregroundColor
	* @return string
	*/
	public static function get($string, $foregroundColor = null) 
	{
		if (!Config::get('colorStatus')) {
			return $string;
		}
		$colored_string = "";
		if ($foregroundColor !== null && isset(self::$foregroundColors[$foregroundColor])) {
			$colored_string .= "\033[" . self::$foregroundColors[$foregroundColor] . "m";			
		}
		// Add string and end coloring
		$colored_string .=  $string . "\033[0m";
		return $colored_string;	
	}

}

