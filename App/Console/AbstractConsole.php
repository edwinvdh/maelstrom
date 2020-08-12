<?php

namespace App\Console;

use App\Console\ConsoleInterface\CommandLineDescriptionInterface;
use App\Console\ConsoleInterface\CommandLineOptionInterface;
use App\Console\ConsoleInterface\ConsoleInterface;

abstract class AbstractConsole implements ConsoleInterface
{
    protected CommandLineOptionInterface $commandLineOption;

    protected CommandLineDescriptionInterface $commandLineDescription;

    public function __construct(CommandLineOptionInterface $commandLineOption, CommandLineDescriptionInterface $commandLineDescription)
    {
        $this->commandLineOption = $commandLineOption;
        $this->commandLineDescription = $commandLineDescription;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }

    /**
     * @return CommandLineOptionInterface
     */
    public function getCommandLineOption(): CommandLineOptionInterface
    {
        return $this->commandLineOption;
    }

    /**
     * @return CommandLineDescriptionInterface
     */
    public function getCommandLineDescription(): CommandLineDescriptionInterface
    {
        return $this->commandLineDescription;
    }
}