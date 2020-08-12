<?php

namespace App\Helper;

use App\Helper\Exception\ClassInvokerException;
use App\Helper\Exception\ClassMethodException;
use Bootstrap;

class ClassInvoker
{
    private string $class;

    private string $method;

    /**
     * ClassInvoker constructor.
     * @param string $class
     * @param string $method
     */
    public function __construct(string $class, $method = '')
    {
        $this->class = $class;
        $this->method = $method;
    }

    /**
     * @param Request|null $request
     * @return mixed
     * @throws ClassInvokerException
     * @throws ClassMethodException
     */
    public function run(?Request $request)
    {
        $object = $this->getClass();
        if (!method_exists($object, $this->method)) {
            throw new ClassMethodException(ClassMethodException::CLASS_NOT_FOUND . ' ' . get_class($object) . ' with method ' . $this->method);
        }

        return call_user_func([$object, $this->method], $request);
    }

    /**
     * return object
     * @throws ClassInvokerException
     */
    public function getClass(): object
    {
        $bootstrap = new Bootstrap();
        if ($this->isValidController()) {
            return new $this->class($bootstrap);
        }
    }

    /**
     * @return bool
     * @throws ClassInvokerException
     */
    private function isValidController()
    {
        if (!class_exists($this->class)) {
            throw new ClassInvokerException();
        }

        return true;
    }
}