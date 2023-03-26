<?php

namespace HJerichen\Framework\View;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\View\TemplateParser\TemplateParser;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>dw
 */
class View
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly TemplateParser $templateParser,
    ) {
    }

    public function parseTemplate(string $template, array $parameters = []): string
    {
        $templateFile = "{$this->configuration->getTemplateRootPath()}/$template";
        return $this->templateParser->parseTemplate($templateFile, $parameters);
    }
}