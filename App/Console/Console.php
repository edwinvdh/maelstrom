<?php

namespace App\Console;

use App\Console\ConsoleInterface\CommandLineOptionInterface;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * My first artisan ;)
 * Ok, not the best I know. But I needed to have a simple command line interface to hack my way through database things.
 */
class Console
{
    // pathinfo sections as constants.
    const FILE_PART_EXTENSION = 'extension';
    const FILE_PART_FILENAME = 'filename';

    /**
     * @param array $arguments
     * @throws ReflectionException
     */
    public function __construct(array $arguments)
    {
        // display all commands
        if (!isset($arguments[1])) {
            $this->getAllConsoleFiles();
        }

        if (isset($arguments[1])) {
            $this->executeCommand($arguments[1], $arguments[2]);
        }
    }

    /**
     * @param string $commandOption
     * @param string|null $parameter
     * @throws ReflectionException
     */
    private function executeCommand(string $commandOption, ?string $parameter)
    {
        $commandFired = false;
        $dir = scandir(__DIR__);
        foreach ($dir as $file) {
            if ($this->isConsoleFile(__DIR__ . DIRECTORY_SEPARATOR . $file)) {
                $class = $this->getNameSpace() . '\\' . $this->getClassName($file);
                $commandLineOption = $this->getClassOption($class);
                if ($commandLineOption == $commandOption) {
                    call_user_func([$class, 'execute'], $parameter);
                    $commandFired = true;
                }
            }
        }

        // display command if execute did not happen.
        if (!$commandFired) {
            $this->getAllConsoleFiles();
        }
    }
    /**
     * @return array
     */
    private function getAllConsoleFiles(): array
    {
        print 'Available commands:'.PHP_EOL.PHP_EOL;

        $return = [];

        $dir = scandir(__DIR__);
        foreach ($dir as $file) {
            if ($this->isConsoleFile(__DIR__ . DIRECTORY_SEPARATOR . $file)) {
                try {
                    $class = $this->getNameSpace() . '\\' . $this->getClassName($file);
                    $commandLineOption = $this->getClassOption($class);
                    $commandLineDescription = $this->getClassDescription($class);
                    if ($commandLineOption != '' and $commandLineDescription != '') {
                        $this->printLine($commandLineOption, $commandLineDescription);
                    }
                } catch (Exception $exception) {
                    print $exception;
                }
            }
        }

        return $return;
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    private function getNameSpace()
    {
        $reflectionClassThis = new ReflectionClass($this);
        return $reflectionClassThis->getNamespaceName();
    }

    /**
     * @param string $class
     * @return string
     * @throws ReflectionException
     */
    private function getClassOption(string $class): string
    {
        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->hasProperty('commandLineOption') or $reflectionClass->isAbstract()) {
            return '';
        }
        $command = new $class;

        return $command->getCommandLineOption()->getCommandLineOption();
    }

    /**
     * @param string $class
     * @return string
     * @throws ReflectionException
     */
    private function getClassDescription(string $class): string
    {
        $reflectionClass = new ReflectionClass($class);
        if (!$reflectionClass->hasProperty('commandLineDescription') or $reflectionClass->isAbstract()) {
            return '';
        }
        $command = new $class;

        return $command->getCommandLineDescription()->getCommandLineDescription();
    }


    /**
     * @param string $file
     * @return bool
     */
    private function isConsoleFile(string $file)
    {
        if (!is_file($file)) {
            return false;
        }

        $fileParts = pathinfo($file);
        if ($fileParts[self::FILE_PART_EXTENSION] != 'php') {
            return false;
        }

        return true;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getClassName(string $file): string
    {
        $fileParts = pathinfo($file);
        return $fileParts[self::FILE_PART_FILENAME];
    }

    private function printLine($option, $description)
    {
        print str_pad($option, 20, ' ', STR_PAD_RIGHT);
        print $description;
        print PHP_EOL;
        return;
    }
}
