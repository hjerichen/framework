<?php

namespace HJerichen\Framework\Route;

use HJerichen\Framework\Request\Request;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class RouteEvaluator
{
    /**
     * @var Route
     */
    private $route;
    /**
     * @var Request
     */
    private $request;

    public function evaluateRouteForRequest(RouteInterface $route, Request $request): bool
    {
        $this->route = $route;
        $this->request = $request;

        if (!$this->isRouteMatching())
            return false;

        $this->extractParametersToRequest();
        return true;
    }

    private function isRouteMatching(): bool
    {
        $uriPartsFromRoute = $this->explodeUriFromRoute();
        $uriPartsFromRequest = $this->explodeUriFromRequest();
        $uriPartsFromRouteCount = count($uriPartsFromRoute);
        $uriPartsFromRequestCount = count($uriPartsFromRequest);

        if ($uriPartsFromRouteCount !== $uriPartsFromRequestCount)
            return false;

        for ($i = 0; $i < $uriPartsFromRouteCount; $i++) {
            if ($this->isParameter($uriPartsFromRoute[$i]))
                continue;
            if ($uriPartsFromRoute[$i] !== $uriPartsFromRequest[$i])
                return false;
        }
        return true;
    }

    private function extractParametersToRequest(): void
    {
        $uriPartsFromRoute = $this->explodeUriFromRoute();
        $uriPartsFromRequest = $this->explodeUriFromRequest();
        $uriPartsFromRouteCount = count($uriPartsFromRoute);

        for ($i = 0; $i < $uriPartsFromRouteCount; $i++) {
            if ($this->isParameter($uriPartsFromRoute[$i])) {
                $parameterName = $this->extractParameterName($uriPartsFromRoute[$i]);
                $this->request->addArgument($parameterName, $uriPartsFromRequest[$i]);
            }
        }
    }

    /**
     * @return array<string>
     */
    private function explodeUriFromRoute(): array
    {
        return explode('/', $this->route->getUri());
    }

    /**
     * @return array<string>
     */
    private function explodeUriFromRequest(): array
    {
        return explode('/', $this->request->getUri());
    }

    private function isParameter(string $routePart): bool
    {
        return strpos($routePart, '{') === 0;
    }

    private function extractParameterName(string $routePart): string
    {
        return str_replace(['{', '}'], '', $routePart);
    }
}