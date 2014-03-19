<?php

class Main
{

	private static $exData = 'Ozzy';

	public function setStatic()
	{
		self::$exData = 'Adem';
	}

	public function getStatic()
	{
		return self::$exData;
	}

}

class Sub extends Main
{


	public function setStatic()
	{
		parent::setStatic();
	}

}


$main = new Main();
$sub = new Sub($main);

echo "Main->data: ".$main->getStatic()." \n";
$sub->setStatic();
echo "setting...\n";
echo "Main->data: ".$main->getStatic()." \n";
