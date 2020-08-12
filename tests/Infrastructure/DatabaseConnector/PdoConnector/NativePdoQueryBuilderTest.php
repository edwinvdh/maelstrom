<?php

namespace Infrastructure\DatabaseConnector\PdoConnector;

use AbstractTestCase;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoConnector;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoQueryBuilder;

class NativePdoQueryBuilderTest extends AbstractTestCase
{
    protected NativePdoConnector $databaseConnector;

    public function setUp(): void
    {
        $this->databaseConnector = new NativePdoConnector(
            getenv('MYSQL_DATABASE'),
            getenv('MYSQL_HOST'),
            getenv('MYSQL_PORT'),
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD')
        );

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testGetAllWithFailure()
    {
        $nativeQueryBuilder = new NativePdoQueryBuilder($this->databaseConnector, 'someTable', 'someDefaultSortOrder', NativePdoQueryBuilder::SORT_ORDER_ASCENDING);
        $actual = $nativeQueryBuilder->getAll();
        $this->assertIsArray($actual);
    }
}