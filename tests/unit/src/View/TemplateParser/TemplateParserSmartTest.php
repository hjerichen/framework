<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use HJerichen\Framework\View\Exception\TemplateParserException;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class TemplateParserSmartTest extends TestCase
{
    use PHPProphetTrait;

    /** @var TemplateParserSmart */
    private $templateParserSmart;

    /** @var TemplateParserCollection */
    private $templateParserCollection;

    /** @var TemplateParser | ObjectProphecy */
    private $templateParser1;

    /** @var TemplateParser | ObjectProphecy */
    private $templateParser2;

    /** @var NamespaceProphecy */
    private $php;

    protected function setUp(): void
    {
        parent::setUp();

        $this->templateParserCollection = new TemplateParserCollection();
        $this->templateParser1 = $this->prophesize(TemplateParser::class);
        $this->templateParser2 = $this->prophesize(TemplateParser::class);
        $this->php = $this->prophesizePHP(__NAMESPACE__);

        $this->setUpTemplateParserCollection();
        $this->preparePHPFunctions();

        $this->templateParserSmart = new TemplateParserSmart($this->templateParserCollection);
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(TemplateParser::class, $this->templateParserSmart);
    }

    public function testThrowsExceptionIfFileDoesNotExist(): void
    {
        $templateFile = '/to/file.tpl';

        $this->php->file_exists($templateFile)->willReturn(false);

        $this->expectException(TemplateParserException::class);
        $this->templateParserSmart->parseTemplate($templateFile);
    }

    public function testThrowsExceptionIfAllParsersDoNotSupportTemplateFile(): void
    {
        $templateFile = '/to/file.tpl';
        $exception = new TemplateParserException('test');

        $this->php->file_exists($templateFile)->willReturn(true);

        $this->templateParser1->parseTemplate($templateFile, [])->shouldBeCalledOnce()->willThrow($exception);
        $this->templateParser2->parseTemplate($templateFile, [])->shouldBeCalledOnce()->willThrow($exception);

        $this->expectExceptionObject($exception);
        $this->templateParserSmart->parseTemplate($templateFile);
    }

    public function testParsesTemplateFromCollection(): void
    {
        $templateFile = '/to/file.tpl';
        $exception = new TemplateParserException('test');

        $this->php->file_exists($templateFile)->willReturn(true);

        $this->templateParser1->parseTemplate($templateFile, [])->shouldBeCalledOnce()->willThrow($exception);
        $this->templateParser2->parseTemplate($templateFile, [])->shouldBeCalledOnce()->willReturn('parsed');

        $expected = 'parsed';
        $actual = $this->templateParserSmart->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */

    private function setUpTemplateParserCollection(): void
    {
        $this->templateParserCollection[] = $this->templateParser1->reveal();
        $this->templateParserCollection[] = $this->templateParser2->reveal();
    }

    private function preparePHPFunctions(): void
    {
        $this->php->prepare('file_exists');
    }
}
