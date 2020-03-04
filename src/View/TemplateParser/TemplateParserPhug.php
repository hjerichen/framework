<?php

namespace HJerichen\Framework\View\TemplateParser;

use Phug\Renderer;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserPhug implements TemplateParser
{
    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        return $this->renderer->renderFile($templateFile, $parameters);
    }
}