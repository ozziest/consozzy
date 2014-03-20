<?php 

namespace Ozziest\Consozzy\System\Core;

use Ozziest\Consozzy\System as System;


class Set extends System\Kernel
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
		$this->info('Set Library Index!');
	}

	/**
	* Set Message Level
	*
	* @param  integer $level
	* @return null
	*/
	public function messages($level = false)
	{
		if ($level === false) {
			$this->error('Hatalı parametre gönderildi.');
			return;			
		}
		// Checking parameter type
		$level = (int) $level;
		if (!is_integer($level)) {
			$this->error('Hatalı parametre');
			return;
		}
		// Checking parameter value
		if ($level >= 0 && $level <= 4) {
			// Everyting is ok
			System\Config::set('userMessageStatus', $level);
			$this->success("Mesaj gösterim durumu seviyesi: $level");
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
	public function errors($status = true)
	{
		// Checking variable type
		$status = $status === 'true' ? true : false;
		System\Config::set('userErrorMessageStatus', $status);
		$status = $status === true ? 'true' : 'false';
		$this->success("Hata mesajları durumu: $status");
	}

	/**
	* Set Color
	*
	* @param  boolean $status
	* @return null
	*/
	public function colors($status)
	{
		// Checking variable type
		$status = $status === 'true' ? true : false;
		System\Config::set('colorStatus', $status);
		$status = $status === true ? 'true' : 'false';
		$this->success("Renk Durumu: $status");		
	}

}

