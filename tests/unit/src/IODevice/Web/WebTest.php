<?php

namespace HJerichen\Framework\IODevice\Web;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class WebTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var Web
     */
    private $web;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->web = new Web();
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = IODevice::class;
        $actual = $this->web;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testSimpleCall(): void
    {
        $this->setUpRequestUri('/');

        $expectedUri = '/';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testSimpleCallWithRoute(): void
    {
        $this->setUpRequestUri('/test');

        $expectedUri = '/test';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testSimpleCallWithLongRoute(): void
    {
        $this->setUpRequestUri('/test/jon');

        $expectedUri = '/test/jon';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testCallWithStringGetArgument(): void
    {
        $this->setUpRequestUri('/test?name=jon');

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['name' => 'jon']);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithBooleanGetArgument(): void
    {
        $this->setUpRequestUri('/test?name');

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['name' => true]);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithArrayGetArgument(): void
    {
        $this->setUpRequestUri('/test?names[]=jon&names[]=max');

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['names' => ['jon', 'max']]);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithMultipleArguments(): void
    {
        $this->setUpRequestUri('/test?test&names[]=jon&names[]=max');

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['test' => true, 'names' => ['jon', 'max']]);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputsContent(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $response = new TextResponse('test');

        $this->expectOutputString('test');
        $php->header('HTTP/1.0 200')->shouldBeCalledOnce();
        $php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputForUnknownRoute(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $exception = new UnknownRouteException(new Request('/test'));
        $response = new TextResponse('test');
        $response->setException($exception);

        $this->expectOutputString('No route found for URI: /test');
        $php->header('HTTP/1.0 404')->shouldBeCalledOnce();
        $php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputForResponseException(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $exception = new ResponseException('exception message');
        $response = new TextResponse('test');
        $response->setException($exception);

        $this->expectOutputString('exception message');
        $php->header('HTTP/1.0 500')->shouldBeCalledOnce();
        $php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputsHtmlContent(): void
    {
        $php = $this->prophesizePHP(__NAMESPACE__);
        $response = new HtmlResponse('test');

        $this->expectOutputString('test');
        $php->header('HTTP/1.0 200')->shouldBeCalledOnce();
        $php->header('Content-type: text/html')->shouldBeCalledOnce();
        $php->reveal();

        $this->web->outputResponse($response);
    }


    /* HELPERS */

    private function setUpRequestUri(string $requestUri): void
    {
        $_SERVER['REQUEST_URI'] = $requestUri;
    }

    private function assertReturnsRequestWith(string $uri, ?MixedCollection $arguments = null): void
    {
        $arguments = $arguments ?? new MixedCollection();

        $expectedRequest = new Request($uri);
        $expectedRequest->addArguments($arguments);
        $actualRequest = $this->web->getRequest();
        $this->assertEquals($expectedRequest, $actualRequest);
    }
}
