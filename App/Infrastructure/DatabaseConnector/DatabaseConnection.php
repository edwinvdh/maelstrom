<?php

namespace App\Infrastructure\DatabaseConnector;

use \PDO;

/**
 * Immutable Database connector holder. So in theory we could replace the database connection with ease.
 */
class DatabaseConnection implements DatabaseConnectionInterface
{
    protected DatabaseConnectorInterface $databaseConnector;

    public function __construct(DatabaseConnectorInterface $databaseConnector)
    {
        $this->databaseConnector = $databaseConnector;
    }

    /**
     * @return DatabaseConnectorInterface
     */
    public function getDatabaseConnector(): DatabaseConnectorInterface
    {
        return $this->databaseConnector;
    }

    /**
     * @param string $query
     * @return DatabaseConnectorInterface
     */
    public function query(string $query): DatabaseConnectorInterface
    {
        return $this->databaseConnector->query($query);
    }

    public function getAll()
    {
        return $this->databaseConnector->getAll();
    }
}