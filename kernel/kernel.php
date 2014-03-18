<?php if ( ! defined('CONSOLE')) exit('No direct script access allowed');
	
class Kernel
{

	/**
	* Debug Log Options
	*/
	private $log = true;

	/**
	* History
	*
	* Command History
	*/
	private $history = array();

	/**
	* Command History liste
	*/
	private $historyIgnores = array(
		'history',
		'help',
		''
		);

	/**
	* Configuration
	*
	* General configuration variable
	*/
	private $config;

	/**
	* User Message Status
	*/
	private $userMessageStatus;

	/**
	* Color Status Variable
	*/
	private $colorStatus;

	/**
	* Loaded classes array
	*/
	private $loadedClasses = array();

	/**
	* Init
	*
	* Konsol uygulamasÄ±nÄ±n kurulmasÄ± iÅŸlemi.
	* 
	* @return null
	*/
	public function __construct()
	{
		
		/**
		* Load configuration settings
		*/
		require_once('config.php');
		$this->helps = $helps;
		$this->languageSelection = $language;
		$this->userMessageStatus = $userMessageStatus;
		$this->colorStatus = $colorStatus;

		/**
		* Load Colors Class
		*/
		require_once('kernel/colors.php');		
		$this->color = new Colors($this->colorStatus);

		/**
		* Load language file
		*/
		if (!file_exists("language/$language.php")) {
			die("Language file is not found.\n");
		} else {
			require_once("language/$language.php");
			$this->lang = $lang;
		}

		/**
		* Setting error handler
		*/
		set_error_handler(array($this, 'errorHandler'));

		/**
		* Starting listener
		*/
		$this->listener();
	}

	/**
	* Error Handler
	* 
	* OluÅŸan hatalarÄ±n yakanlandÄ±ÄŸÄ± bÃ¶lÃ¼m.
	*
	* @param  string $no
	* @param  string $str
	* @param  string $file
	* @param  string $line
	* @return null
	*/
	public function errorHandler($no = 0, $str, $file = 0, $line = 0)
	{
		$this->error($str);
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
	public function lang($key, $color = null)
	{	
		echo $this->color->getColoredString($this->getLang($key), $color, null)."\n";						
		return true;
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
	public function message($level, $text, $color = null)
	{
		// Checking lang message
		$level = $this->getLang($level);
		if (substr($text, 0, 5) == 'lang:') {
			// Check lang key is defined			
			if (isset($this->lang[substr($text, 5)])) {
				$text = $this->lang[substr($text, 5)]; 				
			} else {
				echo "	CRITICAL: Lang key is not found (language/$this->languageSelection.php): $text \n";
				return false;
			}
		}
		// Write message
		echo "	".$this->color->getColoredString($level.": ".$text, $color, null)."\n";
		return true;
	}

	/**
	* Error
	*
	* Show erroe message
	* 
	* @param  string $text
	* @return boolean
	*/
	public function error($text)
	{
		if ($this->userMessageStatus >= 1) {
			$this->message('lang:error', $text, 'red');
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
		if ($this->userMessageStatus >= 2) {
			$this->message('lang:warning', $text, 'yellow');
		}
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
		if ($this->userMessageStatus >= 3) {
			$this->message('lang:info', $text, 'blue');
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
		if ($this->userMessageStatus >= 4) {
			$this->message('lang:success', $text, 'green');
		}
	}

	/**
	* Get Lang
	*
	* Getting language value
	*
	* @param  string $key
	* @return string
	*/
	private function getLang($key)
	{	
		// Clear key 
		if (substr($key, 0, 5) == 'lang:') {
			$key = substr($key, 5);
		}
		// Check lang key
		if (!isset($this->lang[$key])) {
			echo "	CRITICAL: Lang key is not found (language/$this->languageSelection.php): $key \n";
			return false;
		}
		return $this->lang[$key];
	}

	/**
	* Listener
	*
	* Command listener
	*
	* @return null
	*/
	private function listener()
	{
		// Write welcome message to screen
		$this->lang('welcome', "cyan");
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
				$operator = (object) $this->getCommandClass($command);

				// Write process result
				$this->{$operator->type}($operator->message);

				// Call method 
				if ($operator->status == true) {
					if (method_exists($operator->class, $operator->method)) {
						$operator->class->{$operator->method}();
					} else {
						// method not found
						$this->error("lang:methodNotFound");
					}
				} 


			}

		} while ($command != 'exit');
		// Showing exit message to screen
		$this->cursor();
		$this->lang('exit', 'cyan');
		echo "\n";
		exit(0);  
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
		echo "	history -> Komut geÃ§miÅŸi gÃ¶sterilir.. \n";
		echo "	logon -> Debug modu loglarÄ± gÃ¶sterilir. \n";
		echo "	logoff -> Debug modu loglarÄ± gizlenir. \n";
		echo "\n";
		if (isset($this->config['helps']) && 
			is_array($this->config['helps']) &&
			sizeof($this->config['helps']) > 0) {
			foreach ($this->config['helps'] as $key => $value) {
				echo "	$key -> $value. \n";
			}
		}
	}

	/**
	* Get Command Class
	*
	* Find command libraries
	*
	* @param  string  $command
	* @return boolean
	*/
	private function getCommandClass($command) 
	{	
		// Solving command
		if (strpos($command, ':') === false) {
			return array(	
				'status' => false,
				'class' => false,
				'method' => false,
				'type' => 'error',
				'message' => 'lang:falseCommandStructure'
				);
		} 
		$this->command = explode(":", $command);
		$activeClass = $this->command[0];
		$activeMethod = $this->command[1];

		// Check class is loaded before?
		if (isset($this->loadedClasses[$activeClass])) {
			return array(
				'status' => true,
				'class' => $this->loadedClasses[$activeClass],
				'method' => $activeMethod,
				'type' => 'info',
				'message' => 'lang:classLoadFromMemory'
				);
		}

		// Library is exist?
		if (file_exists('libraries/'.$activeClass.'.php')) {
			// Load library
			require_once('libraries/'.$activeClass.'.php');
			// Checking class
			if (class_exists($activeClass)) {
				// Create new one
				$this->loadedClasses[$activeClass] = new $activeClass($this);
				return array(
					'status' => true,
					'class' => $this->loadedClasses[$activeClass],
					'method' => $activeMethod,
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
			'message' => $returnMessage
			);

	}

	/**
	* Cursor
	*
	* @return null
	*/
	private function cursor()
	{
		if (isset($this->lang['cursor'])) {
			$cursor = trim($this->lang['cursor']);
		} else {
			$cursor = '->';
		}
		echo $this->color->getColoredString($cursor.' ', "cyan", null);								
	}

}



