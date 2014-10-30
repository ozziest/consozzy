<?php namespace Ozziest\Consozzy;

use Exception,
    ReflectionClass;

class Consozzy {

    use Screen;

    /**
     * Running console 
     *
     * @param  string           $command
     * @return null
     */
    public function run($command)
    {
        $this->security();
        $this->welcome();
        if ($command === null) {
            $this->core();
        } else {
            $this->execute($command);
        }
    }

    /**
     * Executing command 
     *
     * @param  string       $command
     * @return null
     */
    private function execute($command)
    {
        if (substr_count($command, ':') !== 3) {
            return $this->writeln('The command is not valid!', 'red');
        }
        list($publisher, $package, $class, $method) = explode(':', $command);
        try {
            $library =  ucfirst($publisher).'\\'.
                        ucfirst($package).'\\'.
                        ucfirst($class);
            $oReflectionClass = new ReflectionClass($library);          
            $instance = new $library();
            if (method_exists($instance, $method)) {
                $instance->{$method}();             
            } else {
                throw new Exception('Command not found!');
            }
        } catch (Exception $e) {
            $this->writeln($e->getMessage(), 'red');
        }
    }

    /**
     * Console core 
     *
     * @return null
     */
    private function core()
    {
        do {
            // Waiting for new command
            $command = readline($this->prompt());
            $this->enter();
            if ($command != 'exit') {
                $this->execute($command);
            }
        } while ($command != 'exit');   
        $this->writeln('Consozzy was closed!', 'yellow');       
        $this->enter(3);
    }

    /**
     * Welcome message 
     *
     * @return null
     */
    private function welcome()
    {
        // Writing welcome messages
        $this->writeln('Consozzy 2.0.0 Is Running!', 'green');
        $this->writeln('Simple console application for developers', 'light_gray');
        $this->enter();
    }

    /**
     * Security method 
     *
     * @return null
     */
    private function security()
    {
        // Checking for security
        if (php_sapi_name() != 'cli') {
            throw new Exception("No direct script access allowed.");
        }       
    }

}