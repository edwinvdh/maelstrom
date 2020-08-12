<?php

namespace Helper;

use AbstractTestCase;
use App\Controller\IndexController;
use App\Helper\Exception\ClassInvokerException;
use App\Helper\Exception\ClassMethodException;
use App\Helper\Request;
use App\Helper\Route;

class RouteTest extends AbstractTestCase
{
    public function testIsInstanceOf()
    {
        $actual = new Route('/', '');
        $this->assertInstanceOf(Route::class, $actual);
    }

    /**
     * @test
     * @dataProvider initialRoute
     * @param string $initialRoute
     */
    public function testIsControllerValid(string $initialRoute)
    {
        $route = new Route($initialRoute, '');

        $actual = $route->getController();
        $this->assertEquals('MyController', $actual);
    }

    /**
     * @test
     * @dataProvider initialRoute
     * @param string $initialRoute
     */
    public function testIsMethodValid(string $initialRoute)
    {
        $route = new Route($initialRoute, '');

        $actual = $route->getMethod();
        $this->assertEquals('SomeMethod', $actual);
    }

    /**
     * @test
     * @dataProvider initialRoute
     * @param string $initialRoute
     */
    public function testIsIdentifierValid(string $initialRoute)
    {
        $route = new Route($initialRoute, '');

        $actual = $route->getIdentifier();
        $this->assertEquals('0123-5678-abc-xyz', $actual);
    }

    /**
     * @test
     * @dataProvider noIdInRoute
     * @param string $initialRoute
     */
    public function testNoIdentifierSet(string $initialRoute)
    {
        $route = new Route($initialRoute, '');

        $actual = $route->getIdentifier();
        $this->assertEquals('', $actual);
    }

    /**
     * @test
     * @dataProvider indexDataProvider
     * @param string $initialRoute
     * @throws ClassInvokerException
     * @throws ClassMethodException
     */
    public function testGetIndex(string $initialRoute)
    {
        $route = new Route($initialRoute, 'App\\Controller');

        $actual = $route->getController();
        $this->assertEquals('IndexController', $actual);

        $actual = $route->getMethod();
        $this->assertEquals(Route::DEFAULT_METHOD, $actual);

        $actual = $route->get(new Request());
        $this->assertInstanceOf(IndexController::class, $actual);
    }

    /**
     * @test
     * @dataProvider loginPostProvider
     * @param string $loginPostRoute
     */
    public function testRouteLoginPost(string $loginPostRoute)
    {
        $route = new Route($loginPostRoute, '');

        $actual = $route->getMethod();
        $this->assertEquals('post', $actual);
    }

    /**
     * @return array
     */
    public function initialRoute(): array
    {
        return [
           'ValidRoute' => ['Route' => '/MyController/SomeMethod/0123-5678-abc-xyz'],
           'NotSoHappyRoute' => ['Route' => 'MyController/SomeMethod/0123-5678-abc-xyz'],
       ];
    }

    /**
     * @return array
     */
    public function noIdInRoute(): array
    {
        return [
            'ValidRoute' => ['Route' => '/MyController/SomeMethod/'],
            'ValidRouteNoEndSlash' => ['Route' => '/MyController/SomeMethod'],
            'ValidRouteNoSlashes' => ['Route' => 'MyController/SomeMethod'],
        ];
    }

    /**
     * @return array
     */
    public function indexDataProvider(): array
    {
        return [
            'index' => ['Route' => '/'],
        ];
    }

    /**
     * @return array
     */
    public function loginPostProvider(): array
    {
        return [
            'index' => ['Route' => '/login/post'],
        ];
    }
}
