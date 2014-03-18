<?php 

class Sample
{

	/**
	* Kernel
	*/
	private $kernel;

	public function __construct(&$kernel)
	{
		$this->kernel = $kernel;
	}

	public function test()
	{
		$this->kernel->success('Everything is ok');
	}

}