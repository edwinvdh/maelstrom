<?php

use App\Helper\Request;
use App\Helper\Route;

$request = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

require_once(__DIR__  . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Bootstrap.php');
$bootstrap = new Bootstrap();
$class = $_SERVER['REQUEST_URI'];

$route = new Route($class, 'App\\Controller');
$route->gateWatcher(new Request());
