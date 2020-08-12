<?php

namespace App\Infrastructure\DatabaseConnector\PdoConnector;

use App\Infrastructure\DatabaseConnector\DatabaseConnectorInterface;
use App\Infrastructure\DatabaseConnector\Exception\InvalidConnectorException;
use PDO;
use PDOStatement;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class NativePdoConnector
 * @package App\Infrastructure\DatabaseConnector\PdoConnector
 */
class NativePdoConnector implements DatabaseConnectorInterface
{
    private PDO $databaseConnector;

    private string $databaseName;

    private PDOStatement $pdoStatement;

    private array $bindings;

    /**
     * @return PDOStatement
     */
    public function getPdoStatement(): PDOStatement
    {
        return $this->pdoStatement;
    }

    /**
     * NativePdoConnector constructor.
     * @param string $dbName
     * @param string $dbHost
     * @param int $dbPort
     * @param string $dbUser
     * @param string $dbPassword
     * @param bool $debug
     * @throws InvalidConnectorException
     */
    public function __construct(string $dbName, string $dbHost, int $dbPort, string $dbUser, string $dbPassword, bool $debug = false)
    {
        if (!$this->validateConnection($dbName, $dbHost, $dbPort, $dbUser, $dbPassword)) {
            throw new InvalidConnectorException();
        }
        $this->databaseName = $dbName;
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%d', $dbName, $dbHost, $dbPort);
        $this->databaseConnector = new PDO($dsn, $dbUser, $dbPassword);
        if ($debug) {
            $this->databaseConnector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * @param string $dbName
     * @param string $dbHost
     * @param string $dbPort
     * @param string $dbUser
     * @param string $dbPassword
     *
     * @return bool
     */
    private function validateConnection(
        string $dbName,
        string $dbHost,
        string $dbPort,
        string $dbUser,
        string $dbPassword
    ) {
        if (empty($dbName) or empty($dbHost) or empty($dbPort) or empty($dbUser) or empty($dbPassword)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $query
     * @return DatabaseConnectorInterface
     */
    public function query(string $query): DatabaseConnectorInterface
    {
        $this->resetPdoStatement();
        $this->pdoStatement = $this->databaseConnector->prepare($query);;
        return $this;
    }

    /**
     * @return bool
     */
    public function execute():bool
    {
        return $this->pdoStatement->execute();
    }

    /**
     * @return array
     */
    public function executeQuery(): array
    {
        $bindings = $this->getCleanBindings();
        if (count($bindings) > 0) {
            $results = $this->pdoStatement->execute($bindings);
        } else {
            $results = $this->pdoStatement->execute();
        }

        if (!$results) {
            $this->resetPdoStatement();
            return ['failure' => true];
        }

        $return = [];
        foreach ($this->pdoStatement as $row) {
            $return[] = $row;
        }

        // Reset pdo and bindings.
        $this->resetPdoStatement();

        return $return;
    }

    /**
     * @param string $query
     * @return array
     */
    public function getResultsFromRawQuery(string $query): array
    {
        $statement = $this->databaseConnector->prepare($query);

        $results = $statement->execute();

        if (!$results) {
            return [];
        }

        $return = [];
        foreach ($statement as $row) {
            $return[] = $row;
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $paramType
     * @return DatabaseConnectorInterface
     */
    public function parameter(string $key, string $value, string $paramType): DatabaseConnectorInterface
    {
      $this->pdoStatement->bindValue($key, $value, $paramType);

      return $this;
    }
    /**
     * @param $key
     * @param $value
     * @return DatabaseConnectorInterface
     */
    public function bind($key, $value): DatabaseConnectorInterface
    {
        $this->bindings[$key] = $value;

        return $this;
    }

    private function getCleanBindings()
    {
        $return = [];
        foreach ($this->bindings as $key => $value) {
            $return [] = $value;
        }

        return $return;
    }

    private function resetPdoStatement()
    {
        $this->bindings = [];
        $this->pdoStatement = new PDOStatement();
    }
}