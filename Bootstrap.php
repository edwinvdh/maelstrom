<?php
// I could call this Glue.exe ;)

use App\Console\Console;
use App\Helper\Session;
use App\Infrastructure\DatabaseConnector\DatabaseConnection;
use App\Infrastructure\DatabaseConnector\PdoConnector\NativePdoConnector;
use App\Infrastructure\EnvProvider\EnvProvider;
use App\Repository\UserRepository;

class Bootstrap
{
    private string $rootDir;

    private DatabaseConnection $databaseConnection;

    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->databaseConnection = new DatabaseConnection(
            new NativePdoConnector(
                getenv('MYSQL_DATABASE'),
                getenv('MYSQL_HOST'),
                getenv('MYSQL_PORT'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD')
            )
        );

        $this->rootDir = __DIR__;

        // First time when running...
        // Normally this would be done using the command line ;)
        $dataRepository = new UserRepository($this->databaseConnection);
        if (!$dataRepository->doesTableExist()) {
            define('VERBOSE_OUTPUT', 1);            // this is a dirty 'hack' to avoid header been set error message.
            new Console(['command', 'initDatabase', '']);
            header('Location: /login');
        }
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return DatabaseConnection
     */
    public function getDatabaseConnection(): DatabaseConnection
    {
        return $this->databaseConnection;
    }

    public static function getRootDir()
    {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }
}

/** Base file to load dependencies and required 'stuff' */
/*
 * Composer Packages:
 * vlucas/phpdotenv (for setting up getenv())
 */

$composerAutoloader = 'vendor/autoload.php';
if (!file_exists($composerAutoloader)) {
    new \Exception('Failed to load autoloader. Did you run composer install?');
}

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php');

// PSR-4 autoloader
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AutoLoader.php');

/**
 * Create autoloader, register and use namespace "App". Everything with new App/ gets autoloaded
 */
$autoLoader = new AutoLoader();
$autoLoader->addNamespace('App', __DIR__ . DIRECTORY_SEPARATOR . 'App');
$autoLoader->register();

// Setup getenv.
EnvProvider::getDotEnv(__DIR__);

