<?php

namespace HJerichen\Framework;

use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\Route\Router;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Application
{
    /**
     * @var \HJerichen\Framework\IODevice\IODevice
     */
    private $ioDevice;
    /**
     * @var Router
     */
    private $router;

    public function __construct(IODevice $ioDevice)
    {
        $this->ioDevice = $ioDevice;
        $this->router = new Router();
    }

    public function addRoute(Route $route): void
    {
        $this->router->addRoute($route);
    }

    public function execute(): void
    {
        try {
            $response = $this->route();
        } catch (ResponseException $responseException) {
            $response = $this->createResponseForResponseException($responseException);
        }
        $this->ioDevice->outputResponse($response);
    }

    /**
     * @throws ResponseException
     */
    private function route(): Response
    {
        return $this->router->routeForInput($this->ioDevice);
    }

    private function createResponseForResponseException(ResponseException $responseException): Response
    {
        $response = new Response();
        $response->setException($responseException);
        return $response;
    }
}