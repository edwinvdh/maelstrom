<?php

namespace App\Repository;

use App\Infrastructure\DatabaseConnector\DatabaseConnection;
use App\Infrastructure\DatabaseConnector\DatabaseConnectorInterface;
use PDOException;

abstract class AbstractRepository implements RepositoryInterface
{
    protected DatabaseConnection $databaseConnection;

    protected DatabaseConnectorInterface $databaseConnector;

    protected string $tableName = '';

    public function __construct(DatabaseConnection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
        $this->databaseConnector = $databaseConnection->getDatabaseConnector();
    }

    /**
     * @codeCoverageIgnore
     * This method is just to please the interface for now
     * @return array
     */
    public function getAll(): array
    {
        return $this->databaseConnection->getAll();
    }

    /**
     * @codeCoverageIgnore
     * @return DatabaseConnection
     */
    public function getDatabaseConnection(): DatabaseConnection
    {
        return $this->databaseConnection;
    }

    /**
     * @param DatabaseConnectorInterface $databaseConnector
     * @return array
     */
    protected function executeQuery(DatabaseConnectorInterface $databaseConnector): array
    {
        // @Fixme: Dependency PDOException
        $return = [];

        try {
            $return = $databaseConnector->executeQuery();
        } catch (PDOException $exception) {
            print $exception->getMessage();
        }

        return $return;
    }
}