<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Integration\Phug;

use HJerichen\Framework\Test\Library\TestCase;
use HJerichen\Framework\View\Exception\TemplateParserException;
use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use Phug\Renderer;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserPhugTest extends TestCase
{
    use PHPProphetTrait;

    private TemplateParserPhug $templateParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createNewTemplateParserForTest();
    }

    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = TemplateParser::class;
        $actual = $this->templateParser;
        self::assertInstanceOf($expected, $actual);
    }

    public function testSimpleTemplateParsing(): void
    {
        $templateFile = $this->getTemplateFile('simple-parsing');

        $expected = '<div>simple-parsing</div>';
        $actual = $this->templateParser->parseTemplate($templateFile);
        self::assertEquals($expected, $actual);
    }

    public function testTemplateParsingWithParameter(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter');
        $parameters = ['name' => 'jon'];

        $expected = '<div>name: jon doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        self::assertEquals($expected, $actual);
    }

    public function testEscapingForVariableInText(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter');
        $parameters = ['name' => '<b>jon</b>'];

        $expected = '<div>name: &lt;b&gt;jon&lt;/b&gt; doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        self::assertEquals($expected, $actual);
    }

    public function testWithoutEscapingForVariableInText(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter-unescaped');
        $parameters = ['name' => '<b>jon</b>'];

        $expected = '<div>name: <b>jon</b> doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        self::assertEquals($expected, $actual);
    }

    public function testThrowsExceptionIfNotPugFile(): void
    {
        $templateFile = '/to/file.tpl';

        $this->expectException(TemplateParserException::class);
        $this->templateParser->parseTemplate($templateFile);
    }

    public function testThrowsExceptionIfFileDoesNotExist(): void
    {
        $templateFile = $this->getTemplateFile('something');

        $this->expectException(TemplateParserException::class);
        $this->templateParser->parseTemplate($templateFile);
    }

    /* HELPERS */

    private function getTemplateFile(string $template): string
    {
        return __DIR__ . "/templates/$template.pug";
    }

    protected function createNewTemplateParserForTest(): void
    {
        $renderer = new Renderer();
        $this->templateParser = new TemplateParserPhug($renderer);
    }
}
