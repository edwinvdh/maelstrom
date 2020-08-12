<?php

namespace App\Infrastructure\PrivateKeyProvider;

/**
 * Immutable class for storing the private key
 */
class PrivateKeyProvider implements PrivateKeyProviderInterface
{
    const PRIVATE_KEY = 'PRIVATE_KEY';

    private string $privateKey;

    public function __construct(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}