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
use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\Framework\Test\Library\Utility\HelperDirectory;
use HJerichen\ProphecyPHP\PHPProphetTrait;
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
        $directoryOfTemplates = new HelperDirectory($this, 'templates');

        $this->configuration->getTemplateEngine()->willReturn('default');
        $this->configuration->getTemplateRootPath()->willReturn($directoryOfTemplates);
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

    private function assertOutputEquals(Response $expectedResponse): void
    {
        $this->ioDevice->outputResponse($expectedResponse)->shouldBeCalledOnce();
        $this->application->execute();
    }
}