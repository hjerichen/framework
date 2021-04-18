<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use Phug\Renderer;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserPhug extends TemplateParser
{
    private array $parameters;

    public function __construct(
        private Renderer $renderer
    ) {
    }

    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        $this->templateFile = $templateFile;
        $this->parameters = $parameters;

        $this->throwExceptionIfFileNotFound();
        $this->throwExceptionIfNotFileWithExtension('pug');

        return $this->renderWithPhug();
    }

    private function renderWithPhug(): string
    {
        return $this->renderer->renderFile($this->templateFile, $this->parameters);
    }
}