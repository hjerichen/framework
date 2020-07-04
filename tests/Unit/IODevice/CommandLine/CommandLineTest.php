<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\CommandLine\CommandLine;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CommandLineTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var CommandLine
     */
    private $commandLine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandLine = new CommandLine();
    }

    protected function tearDown(): void
    {
        global $argv;
        $argv = [];
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = IODevice::class;
        $actual = $this->commandLine;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testSimpleCall(): void
    {
        $this->setUpArgv(['file.php']);

        $expectedUri = '/';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testSimpleCallWithRoute(): void
    {
        $this->setUpArgv(['file.php', 'test']);

        $expectedUri = '/test';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testSimpleCallWithLongRoute(): void
    {
        $this->setUpArgv(['file.php', 'test', 'jon']);

        $expectedUri = '/test/jon';
        $this->assertReturnsRequestWith($expectedUri);
    }

    public function testCallWithBooleanArgument(): void
    {
        $this->setUpArgv(['file.php', 'test', '-o']);

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['o' => true]);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithRealArgument(): void
    {
        $this->setUpArgv(['file.php', 'test', '-o', 'jon']);

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['o' => 'jon']);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithMultipleArguments(): void
    {
        $this->setUpArgv(['file.php', 'test', '--name', 'jon', '-v', '-l', 'doe']);

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['name' => 'jon', 'v' => true, 'l' => 'doe']);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testOutputsContentToStdOut(): void
    {
        $php = $this->prophesizePHP('HJerichen\Framework\IODevice\CommandLine');
        $response = new TextResponse('test');

        $php->fwrite(STDOUT, 'test')->shouldBeCalledOnce();
        $php->reveal();

        $this->commandLine->outputResponse($response);
    }

    public function testOutputsExceptionToStdOut(): void
    {
        $php = $this->prophesizePHP('HJerichen\Framework\IODevice\CommandLine');
        $exception = new ResponseException('exception message');
        $response = new TextResponse('test');
        $response->setException($exception);

        $php->fwrite(STDOUT, 'test')->shouldBeCalledOnce();
        $php->fwrite(STDERR, 'exception message')->shouldBeCalledOnce();
        $php->reveal();

        $this->commandLine->outputResponse($response);
    }


    /* HELPERS */

    private function setUpArgv($arguments): void
    {
        global $argv;
        $argv = $arguments;
    }

    private function assertReturnsRequestWith(string $uri, ?MixedCollection $arguments = null): void
    {
        $arguments = $arguments ?? new MixedCollection();

        $expectedRequest = new Request($uri);
        $expectedRequest->addArguments($arguments);
        $actualRequest = $this->commandLine->getRequest();
        $this->assertEquals($expectedRequest, $actualRequest);
    }
}
