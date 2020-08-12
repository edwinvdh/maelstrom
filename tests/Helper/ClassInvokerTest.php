<?php

namespace Helper;

use AbstractTestCase;
use App\Controller\IndexController;
use App\Helper\ClassInvoker;
use App\Helper\Exception\ClassInvokerException;
use App\Helper\Exception\ClassMethodException;
use App\Helper\Request;
use Exception;

class ClassInvokerTest extends AbstractTestCase
{
    public function testIsException()
    {
        $this->expectException(ClassInvokerException::class);
        $classInvoker = new ClassInvoker('App\\Controller\\InToTheVoid');
        $classInvoker->getClass();  // throws ClassInvokerException
    }

    public function testIsInstanceOfIndexController()
    {
        $classInvoker = new ClassInvoker('App\\Controller\\IndexController');
        $actual = $classInvoker->getClass();
        $this->assertInstanceOf(IndexController::class, $actual);
    }

    public function testCanRunClass()
    {
        $classInvoker = new ClassInvoker('App\\Controller\\IndexController', 'get');
        $actual = $classInvoker->run(new Request());
        $this->assertInstanceOf(IndexController::class, $actual);
    }

    public function testMethodNotFound()
    {
        $this->expectException(ClassMethodException::class);
        $classInvoker = new ClassInvoker('App\\Controller\\IndexController', 'intoTheVoid');
        $actual = $classInvoker->run(new Request());
        $this->assertInstanceOf(IndexController::class, $actual);
    }
}
