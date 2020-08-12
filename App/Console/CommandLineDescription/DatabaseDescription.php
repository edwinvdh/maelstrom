<?php

namespace App\Console\CommandLineDescription;

use App\Console\ConsoleInterface\CommandLineDescriptionInterface;

class DatabaseDescription implements CommandLineDescriptionInterface
{
    public function getCommandLineDescription(): string
    {
        return 'Initialize the database';
    }
}
