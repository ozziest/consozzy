<?php 

/**
 * Kernel
 *
 * @package     ozguradem
 * @subpackage  Consozzy
 * @category    Library
 * @author      Ozgur Adem Isikli
 * @link        https://github.com/ozguradem/consozzy
 */
class Sample extends Kernel 
{

	/**
	* Test
	*
	* @return null
	*/
	public function test($params)
	{
		// Show success message
		$this->success('Sample kütüphanesi, test metotu başarılı bir şekilde çalıştırıldı.');
	}

}