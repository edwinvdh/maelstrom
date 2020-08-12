<?php

namespace App\Infrastructure\EnvProvider;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;

class EnvProvider
{
    private static Dotenv $dotEnv;
    private static EnvProvider $instance;

    public function __construct(string $locationOfDotEnvFile)
    {
        try {
            self::$dotEnv = Dotenv::createImmutable($locationOfDotEnvFile);
            self::$dotEnv->load();
        } catch (InvalidFileException $exception) {
            // Stop here, since we can't do anything from this point on.
            echo $exception->getMessage();
            exit;
        }
    }

    public static function getDotEnv(string $locationOfDotEnvFile)
    {
        new EnvProvider($locationOfDotEnvFile);

        return self::$dotEnv;
    }
}