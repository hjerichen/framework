<?php
/** @noinspection PhpVoidFunctionResultUsedInspection */
declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Route;

use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\Route\RouteEvaluator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class RouteEvaluatorTest extends TestCase
{
    use ProphecyTrait;

    private RouteEvaluator $routeEvaluator;
    /** @var ObjectProphecy<Request> */
    private ObjectProphecy $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->prophesize(Request::class);
        $this->routeEvaluator = new RouteEvaluator();
    }

    /* TESTS */

    public function testForSimpleMatch(): void
    {
        $route = $this->createRoute('/test');
        $uri = '/test';

        $this->expectNoParametersSetToRequest();
        $this->assertRouteIsForUri($route, $uri);
    }

    public function testForSimpleMismatch(): void
    {
        $route = $this->createRoute('/test');
        $uri = '/simple';

        $this->assertRouteIsNotForUri($route, $uri);
    }

    public function testForPathCountMismatch(): void
    {
        $route = $this->createRoute('/test');
        $uri = '/test/test';

        $this->assertRouteIsNotForUri($route, $uri);
    }

    public function testForSingleParameter(): void
    {
        $route = $this->createRoute('/test/{id}');
        $uri = '/test/11';

        $this->expectParametersSetToRequest(['id' => '11']);
        $this->assertRouteIsForUri($route, $uri);
    }

    public function testForSingleParameterInMiddle(): void
    {
        $route = $this->createRoute('/test/{id}/jon');
        $uri = '/test/11/jon';

        $this->expectParametersSetToRequest(['id' => '11']);
        $this->assertRouteIsForUri($route, $uri);
    }

    public function testForMultipleParameter(): void
    {
        $route = $this->createRoute('/test/{id}/{name}');
        $uri = '/test/11/jon';

        $this->expectParametersSetToRequest(['id' => '11', 'name' => 'jon']);
        $this->assertRouteIsForUri($route, $uri);
    }


    /* HELPERS */

    private function createRoute(string $uri): Route
    {
        $route = $this->prophesize(Route::class);
        $route->getUri()->willReturn($uri);
        return $route->reveal();
    }

    /** @param array<string,mixed> $parameters */
    private function expectParametersSetToRequest(array $parameters): void
    {
        /** @var mixed $value */
        foreach ($parameters as $name => $value) {
            $this->request->addArgument($name, $value)->shouldBeCalledOnce();
        }
    }

    private function expectNoParametersSetToRequest(): void
    {
        $this->request->addArgument(Argument::any(), Argument::any())->shouldNotBeCalled();
    }

    private function assertRouteIsForUri(Route $route, string $uri): void
    {
        $this->request->getUri()->willReturn($uri);
        self::assertTrue($this->routeEvaluator->evaluateRouteForRequest($route, $this->request->reveal()));
    }

    private function assertRouteIsNotForUri(Route $route, string $uri): void
    {
        $this->request->getUri()->willReturn($uri);
        $this->expectNoParametersSetToRequest();
        self::assertFalse($this->routeEvaluator->evaluateRouteForRequest($route, $this->request->reveal()));
    }
}
