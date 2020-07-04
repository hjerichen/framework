<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\View\TemplateParser;

use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\Framework\View\Exception\TemplateParserException;
use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\Framework\View\TemplateParser\TemplateParserHtml;
use HJerichen\ProphecyPHP\NamespaceProphecy;
use HJerichen\ProphecyPHP\PHPProphetTrait;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserHtmlTest extends TestCase
{
    use PHPProphetTrait;

    /** @var TemplateParserHtml */
    private $templateParser;
    /** @var NamespaceProphecy */
    private $php;

    public function setUp(): void
    {
        $this->php = $this->prophesizePHP($this->getNamespaceFoClass(TemplateParser::class));
        $this->php->prepare('file_get_contents', 'file_exists');

        $this->templateParser = new TemplateParserHtml();
    }

    public function testClassImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(TemplateParser::class, $this->templateParser);
    }

    public function testCanNotParseOtherThanHtmlFile(): void
    {
        $templateFile = '/to/file.tpl';

        $this->expectException(TemplateParserException::class);
        $this->expectExceptionMessage($templateFile);

        $this->templateParser->parseTemplate($templateFile);
    }

    public function testThrowsExceptionIfFileDoesNotExist(): void
    {
        $templateFile = '/to/file.html';

        $this->php->file_exists($templateFile)->willReturn(false);

        $this->expectException(TemplateParserException::class);
        $this->expectExceptionMessage($templateFile);

        $this->templateParser->parseTemplate($templateFile);
    }

    public function testParsingReturnsContentOfHtmlFile(): void
    {
        $templateFile = '/to/file.html';

        $this->php->file_get_contents($templateFile)->willReturn('content of html file');
        $this->php->file_exists($templateFile)->willReturn(true);
        $this->php->reveal();

        $expected = 'content of html file';
        $actual = $this->templateParser->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }
}