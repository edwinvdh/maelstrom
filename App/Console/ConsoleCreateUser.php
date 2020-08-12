<?php

namespace App\Console;

use App\Console\CommandLineDescription\CreateUserDescription;
use App\Console\CommandLineOption\CreateUserOption;
use App\Infrastructure\DatabaseConnector\DatabaseConnection;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoConnector;
use App\Repository\Entity\User;
use App\Repository\UserRepository;

class ConsoleCreateUser extends AbstractConsole
{
    public function __construct()
    {
        $commandLineOption = new CreateUserOption();
        $commandLineDescription = new CreateUserDescription();
        parent::__construct($commandLineOption, $commandLineDescription);
    }

    public function execute()
    {
        $userName = 'Test';
        $userPassword = 'Testing123@';
        $userEmail = 'test@domain.com';

        $databaseConnection = new DatabaseConnection(
            new NativePdoConnector(
                getenv('MYSQL_DATABASE'),
                getenv('MYSQL_HOST'),
                getenv('MYSQL_PORT'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD')
            )
        );
        $dataRepository = new UserRepository($databaseConnection);

        $user = new User();
        $user->setUserEmail($userEmail);
        $user->setUserName($userName);
        $user->setUserPassword($userPassword);

        $dataRepository->create($user);
        if (!defined('VERBOSE_OUTPUT')) {
            print 'User created '. PHP_EOL;
            print 'Email: ' . $userEmail . PHP_EOL;
            print 'UserName: ' . $userName . PHP_EOL;
            print 'Password: ' . $userPassword . PHP_EOL;
        }
    }
}
