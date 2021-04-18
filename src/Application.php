<?php declare(strict_types=1);

namespace HJerichen\Framework;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\Framework\Route\RouteInterface;
use HJerichen\Framework\Route\Router;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Application
{
    private Router $router;

    public function __construct(
        private IODevice $ioDevice,
        Configuration $configuration
    ) {
        $this->router = new Router(new ObjectFactory($configuration));
    }

    public function addRoute(RouteInterface $route): void
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