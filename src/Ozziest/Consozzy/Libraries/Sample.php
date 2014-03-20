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
	* @return null
	*/
	public function index()
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

}