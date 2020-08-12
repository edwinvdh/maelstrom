<?php

namespace App\Infrastructure\DatabaseConnector;

interface DatabaseConnectionInterface
{
    public function __construct(DatabaseConnectorInterface $databaseConnector);

    public function getDatabaseConnector();
}
