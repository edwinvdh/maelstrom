<?php

namespace App\Console\CommandLineDescription;

use App\Console\ConsoleInterface\CommandLineDescriptionInterface;

class CreateUserDescription implements CommandLineDescriptionInterface
{
    public function getCommandLineDescription(): string
    {
        return 'Create user test@test.com with password test';
    }
}
