<?php

namespace App\Console\CommandLineOption;

use App\Console\ConsoleInterface\CommandLineOptionInterface;

class DatabaseOption implements CommandLineOptionInterface
{
    public function getCommandLineOption(): string
    {
        return 'initDatabase';
    }
}
