<?php

namespace App\Infrastructure\DatabaseConnector;

use PDOStatement;

/**
 * A databaseConnector interface.
 * To strong type the database Connector (instead of using \mysqli, \pdo, \sqlite etc)
 * basic Methods can be added while we go along with this (such as Query db, insert, update etc)
 */
interface DatabaseConnectorInterface
{
    public function __construct(string $dbName, string $dbHost, int $dbPort, string $dbUser, string $dbPassword);

    /**
     * @param string $query
     * @return DatabaseConnectorInterface
     */
    public function query(string $query): DatabaseConnectorInterface;

    public function execute(): bool;

    /**
     * @param string $query
     * @return array
     */
    public function getResultsFromRawQuery(string $query): array;

    /**
     * @param string $key
     * @param string $value
     * @param string $paramType
     * @return DatabaseConnectorInterface
     */
    public function parameter(string $key, string $value, string $paramType): DatabaseConnectorInterface;

    /**
     * @param $key
     * @param $value
     * @return DatabaseConnectorInterface
     */
    public function bind($key, $value): DatabaseConnectorInterface;

    /**
     * @return array
     */
    public function executeQuery(): array;

    /**
     * @return string
     */
    public function getDatabaseName(): string;
}
