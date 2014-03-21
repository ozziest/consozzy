<?php

define('PHPUNIT', TRUE);
require_once __DIR__.'/../src/Ozziest/Consozzy/console.php';

use Ozziest\Consozzy\System as Console;

class ConsoleTest extends PHPUnit_Framework_TestCase
{

    /**
    * Console Object
    */
    public $console = false;

    /**
    * Setups
    */
    public function setUp()
    {
        /**
        * Load console width test mode
        */
        $this->console = new Ozziest\Consozzy\System\Loader('test');       
    }

    /**
    * Testing loader object
    */
    public function testLoaderObject()
    {
        $this->assertTrue(is_object($this->console));
    }

    /**
    * Testing colors
    */
    public function testColors()
    {
        $testString = "\033[0;36mtest\033[0m";
        $result = strcmp($testString, Console\Colors::get('test', 'cyan'));
        $this->assertEquals(0, $result);
    }

}

?>