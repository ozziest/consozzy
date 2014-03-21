<?php

namespace Ozziest\Consozzy\System;


use Ozziest\Consozzy\Libraries;
use Ozziest\Consozzy\System\Core;

/**
 * Kernel
 *
 * @package     ozguradem
 * @subpackage  Consozzy
 * @category    Kernel
 * @author      Ozgur Adem Isikli
 * @link        https://github.com/ozguradem/consozzy
 */
class Kernel extends Loader
{

	/**
	* Command History
	*
	* @var array
	*/
	private $commandHistory = array();

	/**
	* Command History liste
	*
	* @var array
	*/
	private $commandHistoryIgnores = array(
		'history',
		'help',
		''
		);

	/**
	* Core commands
	*
	* @var array
	*/
	private $coreCommands = array(
		'clear',
		'history',
		'help'
		);

	/**
	* Core Libraries
	*
	* @var array
	*/
	private static $coreLibraries = array(
		'set'
		);

	/**
	* Kernel construct
	*/
	public function __construct()
	{

	}

	/**
	* Write Success Message
	*
	* @param string $message
	* @return null
	*/
	public static function success($message)
	{	
		if (Config::get('userMessageStatus') >= 1) {
			self::writeln(
				self::_getPrefix('success').self::_solveMessage($message), 
				'green'
				);			
		}
	}

	/**
	* Write Information Message
	*
	* @param string $message
	* @return null
	*/
	public static function info($message)
	{	
		if (Config::get('userMessageStatus') >= 2) {
			self::writeln(
				self::_getPrefix('info').self::_solveMessage($message), 
				'blue'
				);			
		}
	}

	/**
	* Write Warning Message
	*
	* @param string $message
	* @return null
	*/
	public static function warning($message)
	{	
		if (Config::get('userMessageStatus') >= 3) {
			self::writeln(
				self::_getPrefix('warning').self::_solveMessage($message), 
				'brown'
				);			
		}
	}

	/**
	* Write Error Message
	*
	* @param string $message
	* @return null
	*/
	public static function error($message)
	{	
		if (Config::get('userErrorMessageStatus')) {
			self::writeln(
				self::_getPrefix('error').self::_solveMessage($message), 
				'red'
				);			
		}
	}

	/**
	* Write Message 
	*
	* @param  string $message
	* @param  string $color
	* @return null
	*/
	public static function write($message = '', $color = null)
	{
		if (defined('PHPUNIT') && PHPUNIT === true) return;
		$message = Colors::get($message, $color);
		print($message);
	}

	/**
	* Write Message With Enter
	*
	* @param  string $message
	* @param  string $color
	* @return null
	*/
	public static function writeln($message = '', $color = null)
	{
		if (defined('PHPUNIT') && PHPUNIT === true) return;
		$message = Colors::get($message, $color);
		print($message."\n");
	}

	/**
	* Ready
	*
	* This method listen new sub command.
	* All libraries can use this method for listen to 
	* specefic commands.
	*
	* @return string;
	*/
	public function ready()
	{
		// Cursor 
		$this->_promptSub();
		// Listening for new command from user
		$handle = fopen ("php://stdin","r");
		$command = trim(fgets($handle));
		// Update command history
		if (!in_array($command, $this->commandHistoryIgnores)) {
			array_push($this->commandHistory, $command);	
		}
		return strtolower($command);
	}

	/**
	* Clear
	*
	* Clear screen
	*
	* @return null
	*/
	private function clear() 
	{
	    $clearscreen = chr(27)."[H".chr(27)."[2J";
	    print $clearscreen;
	}

	/**
	* History
	*
	* Liste command history
	* 
	* @return false;
	*/
	private function history()
	{
		$this->writeln('	'.$this->_solveMessage('lang:titleHistory'), 'cyan');
		$count = 1;
		for ($i = sizeof($this->commandHistory) - 1; $i >= 0; $i--) { 
			$this->writeln('	- '.$this->commandHistory[$i], 'blue');
			if (++$count > 10) break;
		}
	}

	/**
	* Help
	*
	* Show help content
	*
	* @return null
	*/
	private function help()
	{
		echo "\n";
		echo "	exit\n";
		echo "	history\n";
		echo "\n";
	}

	/**
	* Init
	*
	* @return null
	*/
	public function _init()
	{
		$this->_open();
		$this->_listener();
		$this->_close();
		exit(0);  
	}

	/**
	* Listener
	*
	* Command listener
	*
	* @return null
	*/
	private function _listener()
	{
		// Listener all the time
		do {
			// Waiting new command.
			$command = '';
			$command = $this->_command();

			/**
			* Checking command is local command
			*/
			if (in_array($command, $this->coreCommands) && method_exists($this, $command)) {
				$this->{$command}();
			} else if ($command != 'exit' && $command != '') {

				// Get Commant Object
				$operator = (object) $this->_getCommandClass($command);

				// Write process result
				$this->{$operator->type}($operator->message);
				// Call method 
				if ($operator->status == true) {
					// Check kernel method
					if (method_exists($this, $operator->method)) {
						// Kernel method error
						$this->error('lang:cannotCallKernelMethod');
					} else {
						// Checking method
						if (method_exists($operator->class, $operator->method)) {
							// Calling method
							$operator->class->{$operator->method}($operator->params);
						} else {
							// method not found
							$this->error($this->_solveMessage('lang:methodNotFound'). " `$operator->method` ");
						}
					}
				} 					

				// Remove class from memory
				unset($operator);
			}
		} while ($command != 'exit');
	}

	/**
	* Get Command Class
	*
	* Find command libraries
	*
	* @param  string  $command
	* @return boolean
	*/
	public function _getCommandClass($command) 
	{	
		// Checking comman structure
		if (strpos($command, ':') === false) {
			$command .= ':index';
		} 
		// Solving command
		$this->command = explode(":", $command);
		$activeClass = $this->command[0];
		$activeMethod = $this->command[1];

		// Solving parameter
		$params = false;
		if (strpos($activeMethod, ' ') !== false) {
			$params = explode(' ', $activeMethod);
			$activeMethod = $params[0];
			unset($params[0]);
			if (sizeof($params) == 1) {
				$params = $params[1];
			}
		}

		if (in_array($activeClass, self::$coreLibraries)) {
			// Setting library path
			$libraryPath = __DIR__.'/Core/';	
		} else {
			// Setting library path
			$libraryPath = __DIR__.'/../Libraries/';
		}
		// Library is exist?
		if (file_exists($libraryPath.$activeClass.'.php')) {
			// Load Library
			if ($libraryPath == __DIR__.'/Core/') {
				// Core class load
				$classTemp = 'Ozziest\\Consozzy\\System\\Core\\'.$activeClass;
				$init = new $classTemp;
			} else {
				// Development class load
				$classTemp = 'Ozziest\\Consozzy\\Libraries\\'.$activeClass;
				$init = new $classTemp;
			}
			return array(
				'status' => true,
				'class' => $init,
				'method' => $activeMethod,
				'params' => $params,
				'type' => 'info',
				'message' => 'lang:classLoad'
				);
		} else {
			// Library not found
			$returnMessage= 'lang:libraryNotFound';
		}

		// Return false result
		return array(	
			'status' => false,
			'class' => false,
			'method' => false,
			'type' => 'error',
			'params' => $params,
			'message' => $returnMessage
			);

	}

	/**
	* Solve Message
	*
	* If message have got `lang:` key in content
	* then get language value from language file.
	*
	* @param  string $message
	* @return null
	*/
	private static function _solveMessage($message)
	{
		// Clear key 
		if (substr($message, 0, 5) == 'lang:') {
			$key = substr($message, 5);
		} else {
			return $message;
		}
		return Language::get(substr($message, 5));
	}

	/**
	* Get Prefix
	*
	* @param  string $type
	* @return string
	*/
	private static function _getPrefix($type)
	{
		if (Config::get('userMessagePrefix')) {
			return '	'.Language::get($type).': ';
		} 
		return '	';
	}

	/**
	* Open
	*
	* Open messages
	*
	* @return null
	*/
	private function _open()
	{
		$this->writeln(Language::get('welcome'). ' (v1.1)', 'cyan');
		$this->writeln();
	}

	/**
	* Close Message
	*
	* @return null
	*/
	private function _close()
	{
		$this->writeln(Language::get('exit'), 'cyan');
		$this->writeln();
	}

	/**
	* Prompt
	*
	* @return null
	*/
	private function _prompt()
	{
		$this->write(Language::get('prompt').' ', Config::get('promptColor'));
	}

	/**
	* Prompt Sub
	*
	* @return null
	*/
	private function _promptSub()
	{
		$this->write('	'.Language::get('prompt').' ', Config::get('promptSubColor'));
	}

	/**
	* Command
	*
	* Listening for new command and keep it
	*
	* @return string
	*/
	private function _command()
	{
		// Cursor 
		$this->_prompt();
		// Listening for new command from user
		$handle = fopen ("php://stdin","r");
		$command = trim(fgets($handle));
		// Update command history
		if (!in_array($command, $this->commandHistoryIgnores)) {
			array_push($this->commandHistory, $command);	
		}
		return strtolower($command);
	}

}