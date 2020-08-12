<?php

namespace Infrastructure\EnvProvider;

use AbstractTestCase;
use App\Infrastructure\EnvProvider\EnvProvider;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class EnvProviderTest extends AbstractTestCase
{
    public function testIsInstanceOf()
    {
        $actual = EnvProvider::getDotEnv($this->locationOfDotEnvFile);
        $this->assertInstanceOf(Dotenv::class, $actual);
    }
}
