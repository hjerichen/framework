<?php

namespace HJerichen\Framework\Configuration;

use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ConfigurationJsonTest extends TestCase
{
    use PHPProphetTrait;

    /**
     * @var ConfigurationJson
     */
    private $configuration;
    /**
     * @var string
     */
    private $configurationFile = '/configuration.json';
    /**
     * @var NamespaceProphecy
     */
    private $php;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->php = $this->prophesizePHP(__NAMESPACE__);

        $this->configuration = new ConfigurationJson($this->configurationFile);
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = Configuration::class;
        $actual = $this->configuration;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testForDefaultTemplateEngine(): void
    {
        $this->setUpConfiguration([]);
        
        $expected = 'default';
        $actual = $this->configuration->getTemplateEngine();
        $this->assertEquals($expected, $actual);
    }

    public function testForTemplateEngine(): void
    {
        $this->setUpConfiguration(['template-engine' => 'phug']);

        $expected = 'phug';
        $actual = $this->configuration->getTemplateEngine();
        $this->assertEquals($expected, $actual);
    }

    public function testForDefaultTemplateRootPath(): void
    {
        $this->setUpConfiguration([]);

        $expected = '/application/tpl';
        $actual = $this->configuration->getTemplateRootPath();
        $this->assertEquals($expected, $actual);
    }

    public function testForTemplateRootPath(): void
    {
        $this->setUpConfiguration(['template-root-path' => '/application/templates']);

        $expected = '/application/templates';
        $actual = $this->configuration->getTemplateRootPath();
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */

    public function setUpConfiguration(array $configuration): void
    {
        $configurationAsJson = json_encode($configuration);
        $this->php->file_get_contents($this->configurationFile)->willReturn($configurationAsJson);
        $this->php->reveal();
    }
}
