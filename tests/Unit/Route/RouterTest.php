<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Route;

use HJerichen\Framework\IODevice\InputDevice;
use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Route\Route;
use HJerichen\Framework\Route\Router;
use HJerichen\Framework\Test\Helpers\TestController;
use HJerichen\Framework\View\View;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use TypeError;

class RouterTest extends TestCase
{
    use ProphecyTrait;

    private Router $router;

    /** @var ObjectProphecy<InputDevice> */
    private ObjectProphecy $inputDevice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inputDevice = $this->prophesize(InputDevice::class);
        $this->inputDevice->getRequest()->willReturn(new Request('/'));

        $objectFactory = $this->setUpObjectFactory();
        $this->router = new Router($objectFactory);
    }

    /* TESTS */

    public function test_routeForInput_withControllerMethodDoesNotReturnResponseObject(): void
    {
        $this->expectExceptionMessage('Controller method needs to return a Response object');
        $this->expectException(TypeError::class);

        $this->router->addRoute(new Route('/', TestController::class, 'wrongResponse'));
        $this->router->routeForInput($this->inputDevice->reveal());
    }

    /* HELPERS */

    private function setUpObjectFactory(): ObjectFactory
    {
        $view = $this->prophesize(View::class)->reveal();
        $controller = new TestController($view);

        $objectFactory = $this->prophesize(ObjectFactory::class);
        $objectFactory->instantiateClass(TestController::class)->willReturn($controller);
        return $objectFactory->reveal();
    }
}
