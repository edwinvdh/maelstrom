<?php

use App\Infrastructure\EnvProvider\EnvProvider;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    public string $locationOfDotEnvFile = '';

    public function setUp(): void
    {
        /** suppress output during tests */
        $this->setOutputCallback(function() {});

        $this->locationOfDotEnvFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        EnvProvider::getDotEnv($this->locationOfDotEnvFile);

        parent::setUp();
    }
}