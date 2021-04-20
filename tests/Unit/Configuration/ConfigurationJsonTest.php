<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Configuration;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\Configuration\ConfigurationJson;
use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ConfigurationJsonTest extends TestCase
{
    use PHPProphetTrait;

    private ConfigurationJson $configuration;
    private NamespaceProphecy $php;
    private string $configurationFile = '/configuration.json';

    protected function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP($this->getNamespaceFoClass(ConfigurationJson::class));

        $this->configuration = new ConfigurationJson($this->configurationFile);
    }

    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = Configuration::class;
        $actual = $this->configuration;
        self::assertInstanceOf($expected, $actual);
    }

    public function testForDefaultTemplateEngine(): void
    {
        $this->setUpConfiguration([]);

        $expected = 'default';
        $actual = $this->configuration->getTemplateEngine();
        self::assertEquals($expected, $actual);
    }

    public function testForTemplateEngine(): void
    {
        $this->setUpConfiguration(['template-engine' => 'phug']);

        $expected = 'phug';
        $actual = $this->configuration->getTemplateEngine();
        self::assertEquals($expected, $actual);
    }

    public function testForDefaultTemplateRootPath(): void
    {
        $this->setUpConfiguration([]);

        $expected = '/application/tpl';
        $actual = $this->configuration->getTemplateRootPath();
        self::assertEquals($expected, $actual);
    }

    public function testForTemplateRootPath(): void
    {
        $this->setUpConfiguration(['template-root-path' => '/application/templates']);

        $expected = '/application/templates';
        $actual = $this->configuration->getTemplateRootPath();
        self::assertEquals($expected, $actual);
    }

    public function testGetCustomValue(): void
    {
        $this->setUpConfiguration(['something' => 'test']);

        $expected = 'test';
        $actual = $this->configuration->getCustomValue('something');
        self::assertEquals($expected, $actual);
    }

    public function testGetCustomValueForNotSet(): void
    {
        $this->setUpConfiguration(['some' => 'test']);

        $actual = $this->configuration->getCustomValue('something');
        self::assertNull($actual);
    }

    /* HELPERS */

    public function setUpConfiguration(array $configuration): void
    {
        $configurationAsJson = json_encode($configuration, JSON_THROW_ON_ERROR);
        $this->php->file_get_contents($this->configurationFile)->willReturn($configurationAsJson);
        $this->php->reveal();
    }
}
