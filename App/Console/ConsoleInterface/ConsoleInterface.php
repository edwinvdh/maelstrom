<?php

namespace App\Console\ConsoleInterface;

interface ConsoleInterface
{
    public function execute();

    public function getCommandLineOption(): CommandLineOptionInterface;

    public function getCommandLineDescription(): CommandLineDescriptionInterface;
}
