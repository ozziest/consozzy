<?php 

namespace Ozziest\Consozzy\Libraries;

use Ozziest\Consozzy\System as System;


class Sample extends System\Kernel
{

	/**
	* Test
	*
	* Sample command process
	*
	* @param  array $params
	* @return null
	*/
	public function index($params)
	{	
		// Sample user message
		$this->success('sample:test command was successfully executed.');
		$this->warning('sample:test command was successfully executed.');
		$this->info('sample:test command was successfully executed.');
		$this->error('sample:test command was successfully executed.');
		// Listening sub command
		$command = $this->ready();
		// Write sample sub command
		$this->warning("New command: $command ");

		if (method_exists($this, $command)) {
			$this->{$command}();
		} else {
			$this->error('Sub command not found');
		}

	}

	/**
	* Sub
	*
	* Sample sub process. 
	*
	* @return null
	*/
	private function sub()
	{
		$this->warning('This is simple sub process');
	}

	/**
	* Load Lang
	*
	* Language file loading sample.
	*
	* @return null
	*/
	public function loadlang()
	{
		$this->warning('Language file loading...');
		if (System\Language::load('sample')) {
			$this->success('Language file was loaded!');
			$this->info('lang:sample_key');
		}
	}

	/**
	* Test Lang
	*
	* Sample language key testing.
	* 
	* @return false;
	*/
	public function testlang()
	{
		$this->info('lang:sample_key');
	}

	/**
	* Error 
	*
	* Error handling test
	*
	* @return null
	*/
	public function test()
	{
		$this->warning('Sample error handler test.');
		$temp = 7/0;
	}

}