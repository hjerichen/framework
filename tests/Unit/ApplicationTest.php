<?php
/** @noinspection PhpVoidFunctionResultUsedInspection */
declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit;

use HJerichen\Framework\Application;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\Route\RouteInterface;
use HJerichen\Framework\Route\ViewRoute;
use HJerichen\Framework\Test\Helpers\TestController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ApplicationTest extends TestCase
{
    use ProphecyTrait;

    private Application $application;
    /** @var ObjectProphecy<IODevice> */
    private ObjectProphecy $ioDevice;
    /** @var ObjectProphecy<Configuration> */
    private ObjectProphecy $configuration;

    public function setUp(): void
    {
        parent::setUp();

        $this->ioDevice = $this->prophesize(IODevice::class);
        $this->configuration = $this->prophesize(Configuration::class);

        $this->setUpConfiguration();

        $this->application = new Application($this->ioDevice->reveal(), $this->configuration->reveal());
    }


    /* TESTS */

    public function testCallingIndex(): void
    {
        $route = new Route('/', TestController::class, 'emptyResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new HtmlResponse();
        $this->assertOutputResponse($expected);
    }

    public function testCallControllerMethodWithDependency(): void
    {
        $route = new Route('/', TestController::class, 'simpleResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new HtmlResponse('simple');
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

        $expected = new HtmlResponse();
        $this->assertOutputResponse($expected);
    }

    public function testCallRouteWithParameter(): void
    {
        $route = new Route('/test/{id}', TestController::class, 'testParameterResponse');
        $this->setUpRoute($route);
        $this->setUpInputUri('/test/44');

        $expected = new HtmlResponse('44');
        $this->assertOutputResponse($expected);
    }

    public function testControllerHasAccessToRequestObject(): void
    {
        $route = new Route('/test/{id}', TestController::class, 'testGetsRequest');
        $this->setUpRoute($route);
        $this->setUpInputUri('/test/44');

        $expected = new HtmlResponse('success - 44');
        $this->assertOutputResponse($expected);
    }

    public function testTemplateParsing(): void
    {
        $route = new Route('/', TestController::class, 'testTemplateParsing');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new HtmlResponse('template-file: /application/tpl/test.tpl');
        $this->assertOutputResponse($expected);
    }

    public function testTemplateParsingWithParameter(): void
    {
        $route = new Route('/{name}', TestController::class, 'testTemplateParsingWithParameter');
        $this->setUpRoute($route);
        $this->setUpInputUri('/jon');

        $expectedParameters = var_export(['name' => 'jon'], true);
        $expected = new HtmlResponse("template-file: /application/tpl/test.tpl\n$expectedParameters");
        $this->assertOutputResponse($expected);
    }

    public function testViewRoute(): void
    {
        $route = new ViewRoute('/', 'index');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expected = new HtmlResponse('template-file: /application/tpl/index.tpl');
        $this->assertOutputResponse($expected);
    }


    /* HELPERS */

    private function setUpConfiguration(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('simple-output');
        $this->configuration->getTemplateRootPath()->willReturn('/application/tpl');
    }

    private function setUpRoute(RouteInterface $route): void
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

    private function assertOutputWithUnknownRouteException(string$uri): void
    {
        $expectedException = new UnknownRouteException(new Request($uri));
        $expected = new TextResponse();
        $expected->setException($expectedException);
        $this->assertOutputResponse($expected);
    }

    private function assertOutputResponse(Response $expected): void
    {
        $this->ioDevice->outputResponse($expected)->shouldBeCalledOnce();
        $this->application->execute();
    }
}
