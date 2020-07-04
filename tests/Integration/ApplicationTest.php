<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Integration;

use HJerichen\Framework\Application;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Route\RouteInterface;
use HJerichen\Framework\Route\ViewRoute;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ApplicationTest extends TestCase
{
    use PHPProphetTrait;

    /** @var Application */
    private $application;
    /** @var IODevice | ObjectProphecy */
    private $ioDevice;
    /** @var Configuration | ObjectProphecy */
    private $configuration;

    public function setUp(): void
    {
        parent::setUp();

        $this->ioDevice = $this->prophesize(IODevice::class);
        $this->configuration = $this->prophesize(Configuration::class);

        $this->preparePHPFunctions();
        $this->setUpConfiguration();

        $this->application = new Application($this->ioDevice->reveal(), $this->configuration->reveal());
    }

    public function testCallingIndexPage(): void
    {
        $route = new ViewRoute('/', 'index');
        $this->setUpRoute($route);
        $this->setUpInputUri('/');

        $expectedResponse = new HtmlResponse('content of index.html');
        $this->assertOutputEquals($expectedResponse);
    }


    /* HELPERS */

    private function setUpConfiguration(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('default');
        $this->configuration->getTemplateRootPath()->willReturn(__DIR__ . '/_ApplicationTest_/templates');
    }

    private function setUpRoute(RouteInterface $route): void
    {
        $this->application->addRoute($route);
    }

    private function setUpInputUri(string $inputUri): void
    {
        $request = new Request($inputUri);
        $this->ioDevice->getRequest()->willReturn($request);
    }

    private function preparePHPFunctions(): void
    {
        $php = $this->prophesizePHP('Phug\Renderer\Profiler');
        $php->prepare('memory_get_usage', 'microtime');

        $php = $this->prophesizePHP('HJerichen\Framework');
        $php->prepare('class_exists');

        $php = $this->prophesizePHP('HJerichen\Framework\View\TemplateParser');
        $php->prepare('file_get_contents', 'file_exists');
    }

    private function assertOutputEquals(Response $expectedResponse): void
    {
        $this->ioDevice->outputResponse($expectedResponse)->shouldBeCalledOnce();
        $this->application->execute();
    }
}