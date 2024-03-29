<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Collections\Primitive\StringCollection;
use HJerichen\Framework\Request\Request;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class RouteEvaluator
{
    private RouteInterface $route;
    private Request $request;

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
            $requestPart = $uriPartsFromRequest->offsetGet($i);
            $routePart = $uriPartsFromRoute->offsetGet($i) ?? '';
            if ($this->isParameter($routePart))
                continue;
            if ($routePart !== $requestPart)
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
            $requestPart = $uriPartsFromRequest->offsetGet($i);
            $routePart = $uriPartsFromRoute->offsetGet($i) ?? '';
            if ($this->isParameter($routePart)) {
                $parameterName = $this->extractParameterName($routePart);
                $this->request->addArgument($parameterName, $requestPart);
            }
        }
    }

    private function explodeUriFromRoute(): StringCollection
    {
        $explode = explode('/', $this->route->getUri());
        return new StringCollection($explode);
    }

    private function explodeUriFromRequest(): StringCollection
    {
        $explode = explode('/', $this->request->getUri());
        return new StringCollection($explode);
    }

    private function isParameter(string $routePart): bool
    {
        return str_starts_with($routePart, '{');
    }

    private function extractParameterName(string $routePart): string
    {
        return str_replace(['{', '}'], '', $routePart);
    }
}