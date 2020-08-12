<?php

namespace App\Infrastructure\DatabaseConnector;

interface DatabaseQueryBuilderInterface
{
    public function getAll(): array;
}
