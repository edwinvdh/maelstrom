<?php

namespace App\Console\CommandLineOption;

use App\Console\ConsoleInterface\CommandLineOptionInterface;

class CreateUserOption implements CommandLineOptionInterface
{
    public function getCommandLineOption(): string
    {
        return 'CreateUser';
    }
}
