<?php

namespace App\Infrastructure\DatabaseConnector\PdoConnector;

use App\Infrastructure\DatabaseConnector\DatabaseConnectorInterface;
use App\Infrastructure\DatabaseConnector\DatabaseQueryBuilderInterface;
use PDO;
use PDOStatement;

class NativePdoQueryBuilder implements DatabaseQueryBuilderInterface
{
    protected string $table;

    protected string $defaultSortOrder;

    protected string $defaultOrderByDirection;

    private NativePdoConnector $databaseConnector;

    const SORT_ORDER_ASCENDING = 'ASC';
    const SORT_ORDER_DESCENDING = 'DESC';

    public function __construct(NativePdoConnector $databaseConnector, string $table, string $defaultSortOrder = '', string $defaultOrderByDirection)
    {
        $this->databaseConnector = $databaseConnector;
        $this->table= $table;
        $this->defaultSortOrder = $defaultSortOrder;
        $this->defaultOrderByDirection = $defaultOrderByDirection;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        if ($this->defaultSortOrder != '') {
            $statement = $this->databaseConnector->query(sprintf('SELECT * from %s order by %s %s', $this->table, $this->defaultSortOrder, $this->defaultOrderByDirection));
        } else {
            $statement = $this->databaseConnector->query(sprintf('SELECT * from %s', $this->table));
        }

        $results = $statement->execute();
        if (!$results) {
            return [];
        }

        /** @var PDOStatement (please stop saying this is a bool... thanks) */
        return $this->getResults($statement);
    }

    /**
     * @param DatabaseConnectorInterface $results
     * @return array
     */
    private function getResults(DatabaseConnectorInterface $results): array
    {
        $return = [];
        foreach ($results as $row) {
            $return[] = $row;
        }

        return $return;
    }
}