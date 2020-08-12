<?php

namespace App\Infrastructure\PrivateKeyProvider;

interface PrivateKeyProviderInterface
{
    public function getPrivateKey(): string;
}
