<?php

namespace View\TemplateParser;

use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use PHPUnit\Framework\TestCase;
use Phug\Renderer;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserPhugTest extends TestCase
{
    /**
     * @var TemplateParserPhug
     */
    private $templateParser;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $renderer = new Renderer();
        $this->templateParser = new TemplateParserPhug($renderer);
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = TemplateParser::class;
        $actual = $this->templateParser;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testSimpleTemplateParsing(): void
    {
        $templateFile = $this->getTemplateFile('simple-parsing');

        $expected = '<div>simple-parsing</div>';
        $actual = $this->templateParser->parseTemplate($templateFile);
        $this->assertEquals($expected, $actual);
    }

    public function testTemplateParsingWithParameter(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter');
        $parameters = ['name' => 'jon'];

        $expected = '<div>name: jon doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        $this->assertEquals($expected, $actual);
    }

    public function testEscapingForVariableInText(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter');
        $parameters = ['name' => '<b>jon</b>'];

        $expected = '<div>name: &lt;b&gt;jon&lt;/b&gt; doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        $this->assertEquals($expected, $actual);
    }

    public function testWithoutEscapingForVariableInText(): void
    {
        $templateFile = $this->getTemplateFile('parsing-with-parameter-unescaped');
        $parameters = ['name' => '<b>jon</b>'];

        $expected = '<div>name: <b>jon</b> doe</div>';
        $actual = $this->templateParser->parseTemplate($templateFile, $parameters);
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */

    private function getTemplateFile(string $template): string
    {
        return __DIR__ . "/templates/{$template}.pug";
    }
}
