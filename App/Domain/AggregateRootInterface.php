<?php

namespace App\Domain;

interface AggregateRootInterface
{
    public function getAll(): array;

    // Todo: define what we'll be returning here.
    public function getById(string $identifier);
}
