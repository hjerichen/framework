<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit;

use HJerichen\ClassInstantiator\ClassInstantiator;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\Framework\View\TemplateParser\DecoratorToAppendFileExtension;
use HJerichen\Framework\View\TemplateParser\TemplateParserCollection;
use HJerichen\Framework\View\TemplateParser\TemplateParserHtml;
use HJerichen\Framework\View\TemplateParser\TemplateParserSimpleOutput;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use HJerichen\Framework\View\TemplateParser\TemplateParserSmart;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use Phug\Renderer;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ObjectFactoryTest extends TestCase
{
    use PHPProphetTrait;
    use ProphecyTrait;

    private ObjectFactory $objectFactory;
    private NamespaceProphecy $php;
    /** @var ObjectProphecy<Configuration> */
    private ObjectProphecy $configuration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = $this->prophesize(Configuration::class);
        $this->php = $this->prophesizePHP($this->getNamespaceFoClass(ObjectFactory::class));

        $this->createNewObjectFactoryForTest();
    }

    /* TESTS */

    public function testClassHasCorrectInterface(): void
    {
        $expected = ClassInstantiator::class;
        $actual = $this->objectFactory;
        self::assertInstanceOf($expected, $actual);
    }

    public function testCreateDefaultTemplateParserInclusivePhug(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('default');
        /** @noinspection PhpUndefinedMethodInspection */
        $this->php->class_exists(Renderer::class)->willReturn(true);
        $this->php->reveal();

        $this->mockPHPFunctionsForPhug();

        $expectedCollection = new TemplateParserCollection();
        $expectedCollection[] = new TemplateParserHtml();
        $expectedCollection[] = new TemplateParserPhug(new Renderer());

        $expected = new TemplateParserSmart($expectedCollection);
        $expected = new DecoratorToAppendFileExtension($expected);
        $actual = $this->objectFactory->createTemplateParser();
        self::assertEquals($expected, $actual);
    }

    public function testCreateDefaultTemplateParserWithoutPhug(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('default');
        /** @noinspection PhpUndefinedMethodInspection */
        $this->php->class_exists(Renderer::class)->willReturn(false);
        $this->php->reveal();

        $this->mockPHPFunctionsForPhug();

        $expectedCollection = new TemplateParserCollection();
        $expectedCollection[] = new TemplateParserHtml();

        $expected = new TemplateParserSmart($expectedCollection);
        $expected = new DecoratorToAppendFileExtension($expected);
        $actual = $this->objectFactory->createTemplateParser();
        self::assertEquals($expected, $actual);
    }

    public function testCreateSimpleOutputTemplateParser(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('simple-output');

        $expected = new TemplateParserSimpleOutput();
        $expected = new DecoratorToAppendFileExtension($expected);
        $actual = $this->objectFactory->createTemplateParser();
        self::assertEquals($expected, $actual);
    }

    public function testCreatePhugTemplateParser(): void
    {
        $this->mockPHPFunctionsForPhug();

        $this->configuration->getTemplateEngine()->willReturn('phug');

        $expected = new TemplateParserPhug(new Renderer());
        $expected = new DecoratorToAppendFileExtension($expected);
        $actual = $this->objectFactory->createTemplateParser();
        self::assertEquals($expected, $actual);
    }

    /* HELPERS */

    private function createNewObjectFactoryForTest(): void
    {
        $this->objectFactory = new ObjectFactory($this->configuration->reveal());
    }

    private function mockPHPFunctionsForPhug(): void
    {
        $php = $this->prophesizePHP($this->getNamespaceFoClass(Renderer\Profiler\Profile::class));

        /** @noinspection PhpUndefinedMethodInspection */
        $php->memory_get_usage()->willReturn(11);
        $php->microtime(true)->willReturn(111);

        $php->reveal();
    }
}
