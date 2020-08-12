<?php

namespace App\Helper;

use App\Controller\ControllerInterface;
use App\Helper\Exception\ClassInvokerException;

/**
 * Route class based on /Controller/Method/[Id]
 * using constructor to set the initial route
 * using get, post, patch, update, delete for doing the REST... pun.
 */
class Route implements RouteInterface
{
    const TRAILING_URI = '/';

    const DEFAULT_CONTROLLER = 'IndexController';

    const DEFAULT_METHOD = 'gateWatcher';

    // Namespace for the controller
    private string $namespace = '';

    // The name of the controller

    private string $controller = '';

    // The function we'll be invoking
    private string $method = '';

    // I'll stick with UUIDs for obvious reasons
    private string $identifier = '';

    // This will be the path parts based on ['controller', 'method, 'identifier']
    private array $uriPathParts = [];

    public function __construct(string $requestUri, string $namespace)
    {
        $requestUri = $this->patchUri($requestUri);

        $this->setUriPathParts($requestUri);

        $this->namespace = $namespace;

        if (isset($this->uriPathParts[1])) {
            $this->controller = $this->uriPathParts[1];
            if ($this->controller != '' and strpos($this->controller, 'Controller') === false) {
                $this->controller .= 'Controller';
                $this->controller=ucfirst(str_replace('/', '', $this->controller));
            }
        }

        if (isset($this->uriPathParts[2])) {
            $this->method = $this->uriPathParts[2];
        }

        if (isset($this->uriPathParts[3])) {
            $this->identifier = $this->uriPathParts[3];
        }

        // Request with calling a page (home)
        if ($this->method == '/' or $this->method == '') {
            $this->method = self::DEFAULT_METHOD;
        }

        // go to default when controller is not set
        if ($this->controller == '') {
            $this->controller = SELF::DEFAULT_CONTROLLER;
            $this->method = self::DEFAULT_METHOD;
        }
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     * @throws ClassInvokerException
     * @throws Exception\ClassMethodException
     */
    public function gateWatcher(Request $request): ControllerInterface
    {
        if ($request->isDelete()) {
            return $this->delete($request);
        }
        if ($request->isPost()) {
            return $this->post($request);
        }

        return $this->get($request);
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     * @throws ClassInvokerException
     * @throws Exception\ClassMethodException
     */
    public function get(Request $request): ControllerInterface
    {
        $class = new ClassInvoker($this->namespace . '\\' . $this->controller, 'get');

        return $class->run($request);
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     * @throws ClassInvokerException
     * @throws Exception\ClassMethodException
     */
    public function post(Request $request): ControllerInterface
    {
        $class = new ClassInvoker($this->namespace . '\\' . $this->controller, 'post');

        return $class->run($request);
    }

    // I am not certain at this point if I will go full RESTfull
    public function put(Request $putRequest)
    {
        // TODO: Implement put() method.
    }

    public function patch(Request $patchRequest)
    {
        // TODO: Implement patch() method.
    }

    public function delete(Request $deleteRequest)
    {
        $class = new ClassInvoker($this->namespace . '\\' . $this->controller, 'delete');

        return $class->run($deleteRequest);
    }

    /**
     * @param string $requestUri
     * @return void
     */
    private function setUriPathParts(string $requestUri): void
    {
        $uriParts = parse_url($requestUri);
        $uriPath = $uriParts['path'];
        $this->uriPathParts = explode('/', $uriPath);

        return;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $requestUri
     * @return string
     */
    private function patchUri(string $requestUri): string
    {
        if (substr($requestUri, 0, 1) != '/') {
            $requestUri = self::TRAILING_URI . $requestUri;
        }
        return $requestUri;
    }
}