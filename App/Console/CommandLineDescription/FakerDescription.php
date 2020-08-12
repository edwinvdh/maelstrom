<?php

namespace App\Console\CommandLineDescription;

use App\Console\ConsoleInterface\CommandLineDescriptionInterface;

class FakerDescription implements CommandLineDescriptionInterface
{
    public function getCommandLineDescription(): string
    {
        return 'database faker';
    }
}
