<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class DecoratorToAppendFileExtensionTest extends TestCase
{
    /** @var DecoratorToAppendFileExtension  */
    private $decorator;
    /** @var ObjectProphecy */
    private $templateParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->templateParser = $this->prophesize(TemplateParser::class);

        $this->decorator = new DecoratorToAppendFileExtension($this->templateParser->reveal());
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(TemplateParser::class, $this->decorator);
    }

    public function testDecoratorExtendsCorrectExtension(): void
    {
        $templateFile = __DIR__ . '/templates/index';
        $templateFileExpected = __DIR__ . '/templates/index.tpl';

        $this->templateParser->parseTemplate($templateFileExpected, [])->willReturn('parsed');

        $expected = 'parsed';
        $actual = $this->decorator->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }

    public function testDecoratorDoesNotExtendExtensionWhenNoFetchingFileFound(): void
    {
        $templateFile = __DIR__ . '/templates/something';
        $templateFileExpected = $templateFile;

        $this->templateParser->parseTemplate($templateFileExpected, [])->willReturn('parsed');

        $expected = 'parsed';
        $actual = $this->decorator->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }

    public function testDecoratorDoesNotExtendExtensionWhenExtensionIsAlreadyInString(): void
    {
        $templateFile = __DIR__ . '/templates/index.tpl';
        $templateFileExpected = $templateFile;

        $this->templateParser->parseTemplate($templateFileExpected, [])->willReturn('parsed');

        $expected = 'parsed';
        $actual = $this->decorator->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }

    public function testDecoratorDoesNotExtendExtensionWhenDirectoryDoesNotExist(): void
    {
        /** @noinspection SpellCheckingInspection */
        $templateFile = '/awdad/aawdwdw/index';
        $templateFileExpected = $templateFile;

        $this->templateParser->parseTemplate($templateFileExpected, [])->willReturn('parsed');

        $expected = 'parsed';
        $actual = $this->decorator->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */
}
