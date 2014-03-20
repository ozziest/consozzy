<?php

namespace Ozziest\Consozzy\System;


use Ozziest\Consozzy\Libraries as Libraries;

/**
 * Kernel
 *
 * @package     ozguradem
 * @subpackage  Consozzy
 * @category    Kernel
 * @author      Ozgur Adem Isikli
 * @link        https://github.com/ozguradem/consozzy
 */
class Kernel
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
	* Init
	*
	* @return null
	*/
	public function _init()
	{
		$this->_open();
		$this->_listener();
	}

	/**
	* Write Message 
	*
	* @param  string $message
	* @param  string $color
	* @return null
	*/
	private static function write($message = '', $color = null)
	{
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
	private static function writeln($message = '', $color = null)
	{
		$message = Colors::get($message, $color);
		print($message."\n");
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
		return $command;
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
		return $command;
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
			$key = substr($key, 5);
		} else {
			return $message;
		}
		return Language::get(substr($message, 5));
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
			self::writeln('	'.self::_solveMessage($message), 'red');
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
		self::writeln('	'.self::_solveMessage($message), 'blue');
	}

	/**
	* Write Success Message
	*
	* @param string $message
	* @return null
	*/
	public static function success($message)
	{	
		self::writeln('	'.self::_solveMessage($message), 'green');
	}

	/**
	* Write Warning Message
	*
	* @param string $message
	* @return null
	*/
	public static function warning($message)
	{	
		self::writeln('	'.self::_solveMessage($message), 'brown');
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
			if (method_exists($this, $command)) {
				$this->{$command}();
			} else if ($command != 'exit' && $command != '') {

				// Get Commant Object
				$operator = (object) $this->_getCommandClass($command);

				// Write process result
				$this->{$operator->type}($operator->message);
				// Call method 
				if ($operator->status == true) {
					if (method_exists($operator->class, $operator->method)) {
						$operator->class->{$operator->method}($operator->params);
					} else {
						// method not found
						$this->error($this->_solveMessage('lang:methodNotFound'). " `$operator->method ");
					}
				} 
				// Remove class from memory
				unset($operator);
			}
		} while ($command != 'exit');
		$this->_close();
		exit(0);  
	}

	/**
	* Get Command Class
	*
	* Find command libraries
	*
	* @param  string  $command
	* @return boolean
	*/
	private function _getCommandClass($command) 
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

		if (in_array($activeClass, $this->coreLibraries)) {
			// Setting library path
			$libraryPath = __DIR__.'/Libraries/';			
		} else {
			// Setting library path
			$libraryPath = __DIR__.'/../Libraries/';			
		}

		// Library is exist?
		if (file_exists($libraryPath.$activeClass.'.php')) {
			return array(
				'status' => true,
				'class' => new Libraries\Sample(),
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

}