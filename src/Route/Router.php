<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\ClassInstantiator\MethodInvoker;
use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\InputDevice;
use HJerichen\Framework\IODevice\Web\Web;
use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\Response;
use TypeError;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Router
{
    /** @var RouteInterface[] */
    private array $routes = [];
    private InputDevice $inputDevice;

    public function __construct(
        private readonly ObjectFactory $objectFactory
    ) {
        $this->inputDevice = new Web();
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

        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress MixedAssignment
         */
        $response = $methodInvoker->invokeMethod($callable, $predefinedArguments->asArray());
        if ($response instanceof Response) return $response;

        throw new TypeError('Controller method needs to return a Response object');
    }

    private function getPredefinedArgumentsForControllerMethod(): MixedCollection
    {
        $request = $this->inputDevice->getRequest();
        $arguments = $request->getArguments();
        $arguments['request'] = $request;
        return $arguments;
    }
}