<?php

namespace Infrastructure\DatabaseConnector;

use App\Infrastructure\DatabaseConnector\DatabaseConnection;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoConnector;

class DateBaseConnectorTest
{
    public function testDatabaseConnector()
    {
        $nativePdoConnector = new NativePdoConnector(
            getenv('MYSQL_DATABASE'),
            getenv('MYSQL_HOST'),
            getenv('MYSQL_PORT'),
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD')
        );

        $databaseConnector = new DatabaseConnection($nativePdoConnector);
        $databaseConnector->getDatabaseConnector();
    }
}
