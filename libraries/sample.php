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
class Sample
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
	* Test
	*
	* @return null
	*/
	public function test($params)
	{
		// Show success message
		$this->kernel->success('Everything is ok');
	}

}