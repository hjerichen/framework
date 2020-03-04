<?php

namespace HJerichen\Framework;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\TestHelpers\TestController;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $application;
    /**
     * @var IODevice | ObjectProphecy
     */
    private $ioDevice;
    /**
     * @var Configuration | ObjectProphecy
     */
    private $configuration;

    public function setUp(): void
    {
        parent::setUp();

        $this->ioDevice = $this->prophesize(IODevice::class);
        $this->configuration = $this->prophesize(Configuration::class);
        $this->configuration->getTemplateEngine()->willReturn('default');
        $this->configuration->getTemplateRootPath()->willReturn('/application/tpl');

        $this->application = new Application($this->ioDevice->reveal(), $this->configuration->reveal());
    }


    /* TESTS */

    public function testCallingIndex(): void
    {
        $route = new Route('/', TestController::class, 'emptyResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new Response();
        $this->assertOutputResponse($expected);
    }

    public function testCallControllerMethodWithDependency(): void
    {
        $route = new Route('/', TestController::class, 'simpleResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new Response('simple');
        $this->assertOutputResponse($expected);
    }

    public function testCallUnknownRoute(): void
    {
        $route = new Route('/', TestController::class, 'emptyResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/test');

        $this->assertOutputWithUnknownRouteException('/test');
    }

    public function testCallSimpleRoute(): void
    {
        $route = new Route('/test', TestController::class, 'emptyResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/test');

        $expected = new Response();
        $this->assertOutputResponse($expected);
    }

    public function testCallRouteWithParameter(): void
    {
        $route = new Route('/test/{id}', TestController::class, 'testParameterResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/test/44');

        $expected = new Response(44);
        $this->assertOutputResponse($expected);
    }

    public function testTemplateParsing(): void
    {
        $route = new Route('/', TestController::class, 'testTemplateParsing');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new Response('template-file: /application/tpl/test.tpl');
        $this->assertOutputResponse($expected);
    }

    public function testTemplateParsingWithParameter(): void
    {
        $route = new Route('/{name}', TestController::class, 'testTemplateParsingWithParameter');
        $this->setUpRoute($route);
        $this->setUpInputUri('/jon');

        $expectedParameters = var_export(['name' => 'jon'], true);
        $expected = new Response("template-file: /application/tpl/test.tpl\n{$expectedParameters}");
        $this->assertOutputResponse($expected);
    }


    /* HELPERS */

    private function assertOutputResponse(Response $expected): void
    {
        $this->ioDevice->outputResponse($expected)->shouldBeCalledOnce();
        $this->application->execute();
    }

    private function setUpRoute(Route $route): void
    {
        $this->application->addRoute($route);
    }

    private function setUpInputUri(string $inputUri): void
    {
        $request = $this->createRequest($inputUri);
        $this->ioDevice->getRequest()->willReturn($request);
    }

    private function createRequest(string $inputUri): Request
    {
        return new Request($inputUri);
    }

    private function assertOutputWithUnknownRouteException($uri): void
    {
        $expectedException = new UnknownRouteException(new Request($uri));
        $expected = new Response();
        $expected->setException($expectedException);
        $this->assertOutputResponse($expected);
    }
}
