<?php namespace Ozziest\Consozzy;

trait Screen {
	 	
 	/**
 	 * Command prompt
 	 *
 	 * @param  string 		$text
 	 * @param  string 		$color 
 	 * @return string
 	 */
	public function prompt()
	{
		return $this->getColored('$consozzy: ', 'yellow');		
	}

 	/**
 	 * Write text on the screen
 	 *
 	 * @param  string 		$text
 	 * @param  string 		$color 
 	 * @return string
 	 */
	public function write($text, $selectedColor = null)
	{
		echo $this->getColored($text, $selectedColor);		
	}

 	/**
 	 * Writeln text on the screen
 	 *
 	 * @param  string 		$text
 	 * @param  string 		$color 
 	 * @return string
 	 */
	public function writeln($text, $selectedColor = null)
	{
		echo $this->getColored($text, $selectedColor)."\n";		
	}	

	/**
	 * Break new line 
	 *
	 * @param  integer 		$line 
	 * @return string
	 */
	public function enter($line = 1)
	{
		$string = "";
		for ($i=0; $i < $line; $i++) { 
			$string .= "\n";
		}
		echo $string;
	}

 	/**
 	 * Printing text on the screen
 	 *
 	 * @param  string 		$text
 	 * @param  string 		$color 
 	 * @return string
 	 */
	private function getColored($text, $selectedColor)
	{
		$colors = [
				'black' => '0;30',
				'dark_gray' => '1;30',
				'blue' => '0;34',
				'light_blue' => '1;34',
				'green' => '0;32',
				'light_green' => '1;32',
				'cyan' => '0;36',
				'light_cyan' => '1;36',
				'red' => '1;31',
				'purple' => '0;35',
				'light_purple' => '1;35',
				'brown' => '0;33',
				'yellow' => '1;33',
				'light_gray' => '0;37',
				'white' => '1;37'
			];
		$string = "";
		if ($selectedColor !== null && isset($colors[$selectedColor])) {
			$string .= "\033[" . $colors[$selectedColor] . "m";			
		}
		// Add string and end coloring
		$string .=  $text . "\033[0m";
		return $string;		
	}

}
