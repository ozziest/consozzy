<?php 
/**
 * Kernel
 *
 * @package     ozguradem
 * @subpackage  Consozzy
 * @category    Kernel
 * @author      Ozgur Adem Isikli
 * @link        https://github.com/ozguradem/consozzy
 */
class Set 
{

	/**
	* Kernel
	*/
	private $kernel;

	/**
	* Construct 
	*
	* Setting kernel variable
	*/
	public function __construct(&$kernel)
	{
		$this->kernel = $kernel;
	}

	/**
	* Set Message Level
	*
	* @param  integer $level
	* @return null
	*/
	public function message($level)
	{
		// Checking parameter type
		$level = (int) $level;
		if (!is_integer($level)) {
			$this->kernel->error('Hatalı parametre');
			return;
		}
		// Checking parameter value
		if ($level >= 0 && $level <= 4) {
			// Everyting is ok
			$this->kernel->userMessageStatus = $level;
			$this->kernel->success('Mesaj durumu ayarlandı.');
		} else {
			// Wrong level
			$this->kernel->error('Lütfen 1-4 arasında bir parametre giriniz.');
		}
	}

	/**
	* Set Error Message Status
	*
	* @param  boolean $status
	* @return null
	*/
	public function error($status = true)
	{
		// Checking variable type
		$status = $status === 'true' ? true : false;
		$this->kernel->userErrorMessageStatus = $status;
		$status = $status === true ? 'true' : 'false';
		$this->kernel->success("Hata mesajları durumu: $status");
	}

} 
