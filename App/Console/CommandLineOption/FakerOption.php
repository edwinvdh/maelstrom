<?php

namespace App\Console\CommandLineOption;

use App\Console\ConsoleInterface\CommandLineOptionInterface;

class FakerOption implements CommandLineOptionInterface
{
    public function getCommandLineOption(): string
    {
        return 'faker';
    }
}
