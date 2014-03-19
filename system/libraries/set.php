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
class Set extends Kernel
{

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
			$this->error('Hatalı parametre');
			return;
		}
		// Checking parameter value
		if ($level >= 0 && $level <= 4) {
			// Everyting is ok
			$this->setUserMessageStatus($level);
			$this->success('Mesaj durumu ayarlandı.');
		} else {
			// Wrong level
			$this->error('Lütfen 1-4 arasında bir parametre giriniz.');
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
		$this->setErrorMessageStatus($status);
		$status = $status === true ? 'true' : 'false';
		$this->success("Hata mesajları durumu: $status");
	}

	/**
	* Set Color
	*
	* @param  boolean $status
	* @return null
	*/
	public function color($status)
	{
		// Checking variable type
		$status = $status === 'true' ? true : false;
		$this->setColorStatus($status);
		$status = $status === true ? 'true' : 'false';
		$this->success("Renk Durumu: $status");		
	}

} 
