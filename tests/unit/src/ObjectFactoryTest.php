<?php declare(strict_types=1);

namespace HJerichen\Framework;

use HJerichen\ClassInstantiator\ClassInstantiator;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\View\TemplateParser\TemplateParserDefault;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ObjectFactoryTest extends TestCase
{
    /**
     * @var ObjectFactory
     */
    private $objectFactory;
    /**
     * @var Configuration | ObjectProphecy
     */
    private $configuration;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = $this->prophesize(Configuration::class);
        $this->configuration->getTemplateEngine()->willReturn('default');

        $this->objectFactory = new ObjectFactory($this->configuration->reveal());
    }


    /* TESTS */

    public function testClassHasCorrectInterface(): void
    {
        $expected = ClassInstantiator::class;
        $actual = $this->objectFactory;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testCreateDefaultTemplateParser(): void
    {
        $expected = new TemplateParserDefault();
        $actual = $this->objectFactory->createTemplateParser();
        $this->assertEquals($expected, $actual);
    }

    public function testCreatePhugTemplateParser(): void
    {
        $this->configuration->getTemplateEngine()->willReturn('phug');

        $expected = TemplateParserPhug::class;
        $actual = $this->objectFactory->createTemplateParser();
        $this->assertInstanceOf($expected, $actual);
    }


    /* HELPERS */
}
