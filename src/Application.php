<?php

namespace HJerichen\Framework;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\Route\Router;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Application
{
    /**
     * @var IODevice
     */
    private $ioDevice;
    /**
     * @var Router
     */
    private $router;

    public function __construct(IODevice $ioDevice, Configuration $configuration)
    {
        $this->ioDevice = $ioDevice;
        $this->router = new Router(new ObjectFactory($configuration));
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
        $response = new TextResponse();
        $response->setException($responseException);
        return $response;
    }
}