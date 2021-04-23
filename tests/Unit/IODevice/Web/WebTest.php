<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\IODevice\Web;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\IODevice\Web\Web;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Exception\UnknownRouteException;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class WebTest extends TestCase
{
    use PHPProphetTrait;

    private Web $web;
    private NamespaceProphecy $php;

    protected function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP($this->getNamespaceFoClass(Web::class));

        $this->web = new Web();
    }

    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = IODevice::class;
        $actual = $this->web;
        self::assertInstanceOf($expected, $actual);
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

    public function testCallWithBody(): void
    {
        $this->setUpRequestUri('/test');
        $this->setUpBody('some body');

        $this->assertReturnsRequestWith(uri: '/test', body: 'some body');
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputsContent(): void
    {
        $response = new TextResponse('test');

        $this->expectOutputString('test');
        $this->php->header('HTTP/1.0 200')->shouldBeCalledOnce();
        $this->php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $this->php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputForUnknownRoute(): void
    {
        $exception = new UnknownRouteException(new Request('/test'));
        $response = new TextResponse('test');
        $response->setException($exception);

        $this->expectOutputString('No route found for URI: /test');
        $this->php->header('HTTP/1.0 404')->shouldBeCalledOnce();
        $this->php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $this->php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputForResponseException(): void
    {
        $exception = new ResponseException('exception message');
        $response = new TextResponse('test');
        $response->setException($exception);

        $this->expectOutputString('exception message');
        $this->php->header('HTTP/1.0 500')->shouldBeCalledOnce();
        $this->php->header('Content-type: text/plain')->shouldBeCalledOnce();
        $this->php->reveal();

        $this->web->outputResponse($response);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testOutputsHtmlContent(): void
    {
        $response = new HtmlResponse('test');

        $this->expectOutputString('test');
        $this->php->header('HTTP/1.0 200')->shouldBeCalledOnce();
        $this->php->header('Content-type: text/html')->shouldBeCalledOnce();
        $this->php->reveal();

        $this->web->outputResponse($response);
    }

    /* HELPERS */

    private function setUpRequestUri(string $requestUri): void
    {
        $_SERVER['REQUEST_URI'] = $requestUri;
    }

    private function setUpBody(string $body): void
    {
        $this->php->file_get_contents('php://input')->willReturn($body);
        $this->php->reveal();
    }

    private function assertReturnsRequestWith(string $uri, ?MixedCollection $arguments = null, string $body = ''): void
    {
        $arguments = $arguments ?? new MixedCollection();

        $expectedRequest = new Request($uri, $body);
        $expectedRequest->addArguments($arguments);
        $actualRequest = $this->web->getRequest();
        self::assertEquals($expectedRequest, $actualRequest);
    }
}
