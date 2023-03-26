<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\CommandLine\CommandLine;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\TextResponse;
use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\ProphecyPHP\PHPProphetTrait;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CommandLineTest extends TestCase
{
    use PHPProphetTrait;

    private CommandLine $commandLine;

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
        self::assertInstanceOf($expected, $actual);
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

    public function testCallWithArgumentsHaveEqualSign(): void
    {
        $this->setUpArgv(['file.php', 'test', '--name=jon', '-v', '-l=doe']);

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['name' => 'jon', 'v' => true, 'l' => 'doe']);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testCallWithLongArgumentsAtLastPosition(): void
    {
        $this->setUpArgv(['file.php', 'test', '--hello']);

        $expectedUri = '/test';
        $expectedArguments = new MixedCollection(['hello' => true]);
        $this->assertReturnsRequestWith($expectedUri, $expectedArguments);
    }

    public function testOutputsContentToStdOut(): void
    {
        $php = $this->prophesizePHP($this->getNamespaceFoClass(CommandLine::class));
        $response = new TextResponse('test');

        $php->fwrite(STDOUT, 'test')->shouldBeCalledOnce();
        $php->reveal();

        $this->commandLine->outputResponse($response);
    }

    public function testOutputsExceptionToStdOut(): void
    {
        $php = $this->prophesizePHP($this->getNamespaceFoClass(CommandLine::class));
        $exception = new ResponseException('exception message');
        $response = new TextResponse('test');
        $response->setException($exception);

        $php->fwrite(STDOUT, 'test')->shouldBeCalledOnce();
        $php->fwrite(STDERR, 'exception message')->shouldBeCalledOnce();
        $php->reveal();

        $this->commandLine->outputResponse($response);
    }

    /* HELPERS */

    /** @param string[] $arguments */
    private function setUpArgv(array $arguments): void
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
        self::assertEquals($expectedRequest, $actualRequest);
    }
}
