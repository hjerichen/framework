<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\ClassInstantiator\MethodInvoker;
use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\InputDevice;
use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Router
{
    /** @var RouteInterface[] */
    private array $routes = [];
    private InputDevice $inputDevice;

    public function __construct(
        private ObjectFactory $objectFactory
    ) {
    }


    public function addRoute(RouteInterface $route): void
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
    private function getRouteForInput(): RouteInterface
    {
        $routeEvaluator = new RouteEvaluator();
        $request = $this->inputDevice->getRequest();

        foreach ($this->routes as $route) {
            if ($routeEvaluator->evaluateRouteForRequest($route, $request)) {
                return $route;
            }
        }
        throw new UnknownRouteException($request);
    }

    private function callRoute(RouteInterface $route): Response
    {
        $methodInvoker = new MethodInvoker($this->objectFactory);

        $controller = $route->getInstantiatedClass($this->objectFactory);
        $callable = [$controller, $route->getMethod()];
        $predefinedArguments = $this->getPredefinedArgumentsForControllerMethod();
        return $methodInvoker->invokeMethod($callable, $predefinedArguments->asArray());
    }

    private function getPredefinedArgumentsForControllerMethod(): MixedCollection
    {
        $request = $this->inputDevice->getRequest();
        return $request->getArguments();
    }
}