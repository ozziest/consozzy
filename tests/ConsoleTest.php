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

    /**
    * Test Configs
    */
    public function testConfigs()
    {	
    	// Test all configs
    	$configs = Console\Config::get();
    	$this->assertTrue(is_object($configs));
    	// Get config elements
    	$this->assertEquals('tr', Console\Config::get('language'));
    	$this->assertEquals(true, Console\Config::get('colorStatus'));
    	$this->assertEquals('green', Console\Config::get('promptColor'));
    	$this->assertEquals('yellow', Console\Config::get('promptSubColor'));
    	$this->assertEquals(3, Console\Config::get('userMessageStatus'));
    	$this->assertEquals(true, Console\Config::get('userMessagePrefix'));
    	$this->assertEquals(true, Console\Config::get('userErrorMessageStatus'));
    	// Setting
    	Console\Config::set('userErrorMessageStatus', false);
    	$this->assertEquals(false, Console\Config::get('userErrorMessageStatus'));
    }

    /**
    * Language tests
    */
    public function testLanguages()
    {
    	// defined keys are testing...
    	$this->assertEquals('->', Console\Language::get('prompt'));
    	$this->assertEquals(false, Console\Language::get('xxxx'));
    	// Load new language file
    	$this->assertEquals(false, Console\Language::get('sample_key'));
    	$this->assertTrue(Console\Language::load('sample'));
    	$this->assertEquals('Sample language value.', Console\Language::get('sample_key'));
    }

    /**
    * Kernel
    */
    public function testKernel()
    {
    	// Custom library testing...
    	$result = Console\Kernel::_getCommandClass('uknowcommand');
    	$this->assertFalse($result['status']);
    	$result = Console\Kernel::_getCommandClass('sample:command');
    	$this->assertTrue($result['status']);
    	// Core library testing..
    	$result = Console\Kernel::_getCommandClass('set:colors false');
    	$this->assertTrue($result['status']);
    }

}

?>