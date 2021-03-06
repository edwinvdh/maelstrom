<?php

namespace App\Console;

use App\Console\CommandLineDescription\FakerDescription;
use App\Console\CommandLineOption\FakerOption;
use App\Infrastructure\DatabaseConnector\DatabaseConnection;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoConnector;
use App\Repository\Entity\User;
use App\Repository\UserRepository;

class ConsoleFaker extends AbstractConsole
{
    public function __construct()
    {
        $commandLineOption = new FakerOption();
        $commandLineDescription = new FakerDescription();
        parent::__construct($commandLineOption, $commandLineDescription);
    }

    public function execute()
    {
        $databaseConnection = new DatabaseConnection(
            new NativePdoConnector(
                getenv('MYSQL_DATABASE'),
                getenv('MYSQL_HOST'),
                getenv('MYSQL_PORT'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD')
            )
        );
        for ($i = 0; $i < 100; $i++) {
            $dataRepository = new UserRepository($databaseConnection);
            $name = self::getRandomName();
            $surname = self::getRandomName();
            $domain = self::getDomain();
            $email = $name.$domain;
            $password = $name.rand(0, 65535). '@!';
            $user = new User();
            $user->setUserEmail($email);
            $user->setUserName($name . ' ' . $surname);
            $user->setUserPassword($password);
            $dataRepository->create($user);
        }

        parent::execute(); // TODO: Change the autogenerated stub
    }

    /**
     * @return string
     */
    public static function getRandomName(): string
    {
        // Here you go, the top 10 baby names of 2018
        $names = [
            'Liam',
            'Emma',
            'Noah',
            'Olivia',
            'William',
            'Ava',
            'James',
            'Isabella',
            'Oliver',
            'Sophia',
            'Benjamin',
            'Charlotte',
            'Elijah',
            'Mia',
            'Lucas',
            'Amelia',
            'Mason',
            'Harper',
            'Logan',
            'Evelyn',
        ];
        return self::getFromCollection($names);
    }

    /**
     * @return string
     */
    public static function getDomain(): string
    {
        $domain = [
            '@gmail.com',
            '@hotmail.com',
            '@yahoo.com',
            '@live.com',
            '@domain.com',
            '@localhost.com',
        ];
        return self::getFromCollection($domain);
    }

    /**
     * @param array $collection
     * @return string
     */
    private static function getFromCollection(array $collection)
    {
        $max = count($collection);
        $random = rand(0, $max-1);
        if (isset($collection[$random])) {
            return $collection[$random];
        }

        return '';
    }
}
