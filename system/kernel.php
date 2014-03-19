<?php if ( ! defined('CONSOLE')) exit('No direct script access allowed');

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
	* Debug Log Options
	*
	* @var boolean
	*/
	private $log = true;

	/**
	* History
	*
	* Command History
	*
	* @var array
	*/
	private $history = array();

	/**
	* Command History liste
	*
	* @var array
	*/
	private $historyIgnores = array(
		'history',
		'help',
		''
		);

	/**
	* Core Library
	*
	* System libraries
	*
	* @var array
	*/
	private $coreLibraries = array(
		'set'
		);

	/**
	* User Message Status
	*
	* @var integer
	*/
	private static $userMessageStatus;

	/**
	* User Error Message Status
	*
	* @var boolean
	*/
	private static $userErrorMessageStatus;

	/**
	* Color Status Variable
	*
	* @var boolean
	*/
	private static $colorStatus;

	/**
	* Color Class Variable
	*
	* @var object
	*/
	private static $color;

	/**
	* Lang Values
	*
	* @var array
	*/
	public static $lang;


	/**
	* Set Error Message Status
	*
	* @param  boolean $status
	* @return null
	*/
	public function setErrorMessageStatus($status)
	{
		self::$userErrorMessageStatus = $status;
	}

	/**
	* Set User Message Status
	*
	* @param  boolean $status
	* @return null
	*/
	public function setUserMessageStatus($status)
	{
		self::$userMessageStatus = $status;
	}

	/**
	* Set Color Status
	*
	* @param  boolean $status
	* @return null
	*/
	public function setColorStatus($status)
	{
		self::$color->setActive($status);
	}

	/**
	* Error
	*
	* Show error message
	* 
	* @param  string $text
	* @return boolean
	*/
	public function error($text)
	{
		if (self::$userErrorMessageStatus === true) {
			$this->_message('lang:error', $text, 'red');
		}
	}

	/**
	* Warning
	*
	* Show warning message
	* 
	* @param  string $text
	* @return null
	*/
	public function warning($text)
	{
		if (self::$userMessageStatus >= 3) {
			$this->_message('lang:warning', $text, 'yellow');
		}
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
	* Cursor
	*
	* @return null
	*/
	private function cursor()
	{
		if (isset(self::$lang['cursor'])) {
			$cursor = trim(self::$lang['cursor']);
		} else {
			$cursor = '->';
		}
		echo self::$color->getColoredString($cursor.' ', "cyan", null);								
	}

	/**
	* Info
	*
	* Show information message
	* 
	* @param  string $text
	* @return null
	*/
	public function info($text)
	{
		if (self::$userMessageStatus >= 2) {
			$this->_message('lang:info', $text, 'blue');
		}
	}

	/**
	* Success
	*
	* Show success message
	* 
	* @param  string $text
	* @return null
	*/
	public function success($text)
	{
		if (self::$userMessageStatus >= 1) {
			$this->_message('lang:success', $text, 'green');
		}
	}

	/**
	* Initialization
	*
	* @return null
	*/
	public function _init()
	{
		/**
		* Load configuration settings
		*/
		require_once('config.php');
		$this->helps = $helps;
		$this->languageSelection = $language;
		self::$userMessageStatus = $userMessageStatus;
		self::$userErrorMessageStatus = $userErrorMessageStatus;
		self::$colorStatus = $colorStatus;

		/**
		* Load Colors Class
		*/
		require_once(KERNEL.'/colors.php');		
		self::$color = new Colors(self::$colorStatus);

		/**
		* Load language file
		*/
		if (!file_exists("language/$language.php")) {
			die("Language file is not found.\n");
		} else {
			require_once("language/$language.php");
			self::$lang = $lang;
		}

		/**
		* Setting error handler
		*/
		set_error_handler(array($this, '_errorHandler'));
		/**
		* Starting listener
		*/
		$this->_listener();
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
		// Write welcome message to screen
		$this->_lang('welcome', "cyan");
		echo "\n";
		// Listener all the time
		do {
			// Cursor 
			$this->cursor();
			// Listening for new command from user
			$handle = fopen ("php://stdin","r");
			$command = trim(fgets($handle));

			// Update command history
			if (!in_array($command, $this->historyIgnores)) {
				array_push($this->history, $command);	
			}

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
						$this->error("lang:methodNotFound");
					}
				} 

				$this->warning('Before Memory Usage => '.memory_get_usage());
				unset($operator);
				$this->warning('After Memory Usage => '.memory_get_usage());


			}

		} while ($command != 'exit');
		// Showing exit message to screen
		$this->cursor();
		$this->_lang('exit', 'cyan');
		echo "\n";
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
			return array(	
				'status' => false,
				'class' => false,
				'method' => false,
				'params' => false,
				'type' => 'error',
				'message' => 'lang:falseCommandStructure'
				);
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

		// Setting library path
		$libraryPath = 'libraries/';
		if (in_array($activeClass, $this->coreLibraries)) {
			$libraryPath = KERNEL.'/libraries/';
		}; 

		// Library is exist?
		if (file_exists($libraryPath.$activeClass.'.php')) {
			// Load library
			require_once($libraryPath.$activeClass.'.php');
			// Checking class
			if (class_exists($activeClass)) {
				return array(
					'status' => true,
					'class' => new $activeClass($this),
					'method' => $activeMethod,
					'params' => $params,
					'type' => 'info',
					'message' => 'lang:classLoadFromDisk'
					);
			} else {
				$returnMessage= 'lang:classNotFound';
			}
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
	* Message 
	*
	* Show message
	*
	* @param  string $level
	* @param  string $text
	* @param  string $color
	* @param  null
	*/
	public function _message($level, $text, $color = null)
	{
		// Checking lang message
		$level = $this->_getLang($level);
		if (substr($text, 0, 5) == 'lang:') {
			// Check lang key is defined			
			if (isset(self::$lang[substr($text, 5)])) {
				$text = self::$lang[substr($text, 5)]; 				
			} else {
				echo "	CRITICAL: Lang key is not found (language/$this->languageSelection.php): $text \n";
				return false;
			}
		}
		// Write message
		echo "	".self::$color->getColoredString($level.": ".$text, $color, null)."\n";
		return true;
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
	private function _errorHandler($no = 0, $str, $file = 0, $line = 0)
	{
		$this->error($str." ($no, $file, $line)");
	}

	/**
	* Lang
	*
	* Write lang message to console
	*
	* @param  string $key
	* @param  string $color
	* @return boolean
	*/
	public function _lang($key, $color = null)
	{	
		echo self::$color->getColoredString($this->_getLang($key), $color, null)."\n";						
		return true;
	}

	/**
	* Get Lang
	*
	* Getting language value
	*
	* @param  string $key
	* @return string
	*/
	private function _getLang($key)
	{	
		// Clear key 
		if (substr($key, 0, 5) == 'lang:') {
			$key = substr($key, 5);
		}
		// Check lang key
		if (!isset(self::$lang[$key])) {
			echo "	CRITICAL: Lang key is not found (language/$this->languageSelection.php): $key \n";
			return false;
		}
		return self::$lang[$key];
	}

}



