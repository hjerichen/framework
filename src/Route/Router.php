<?php

namespace HJerichen\Framework\Route;

use HJerichen\ClassInstantiator\MethodInvoker;
use HJerichen\Framework\IODevice\InputDevice;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Router
{
    /**
     * @var ObjectFactory
     */
    private $objectFactory;
    /**
     * @var IODevice
     */
    private $inputDevice;
    /**
     * @var Route[]
     */
    private $routes = [];

    public function __construct(ObjectFactory $objectFactory)
    {
        $this->objectFactory = $objectFactory;
    }


    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @throws ResponseException
     */
    public function routeForInput(InputDevice $inputDevice): Response
    {
        $this->inputDevice = $inputDevice;

        $route = $this->getRouteForInput();
        return $this->callRoute($route);
    }

    /**
     * @throws ResponseException
     */
    private function getRouteForInput(): Route
    {
        $routeEvaluator = new RouteEvaluator();
        $request = $this->inputDevice->getRequest();

        foreach ($this->routes as $route) {
            if ($routeEvaluator->evaluateRouteForRequest($route, $request))
                return $route;
        }
        throw new UnknownRouteException($request);
    }

    private function callRoute(Route $route): Response
    {
        $methodInvoker = new MethodInvoker($this->objectFactory);

        $controller = $route->getInstantiatedClass($this->objectFactory);
        $callable = [$controller, $route->getMethod()];
        $predefinedArguments = $this->getPredefinedArgumentsForControllerMethod();
        return $methodInvoker->invokeMethod($callable, $predefinedArguments);
    }

    /**
     * @return array<string,mixed>
     */
    private function getPredefinedArgumentsForControllerMethod(): array
    {
        $request = $this->inputDevice->getRequest();
        return $request->getArguments();
    }
}