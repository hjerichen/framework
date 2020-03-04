<?php

namespace HJerichen\Framework\View;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\View\TemplateParser\TemplateParser;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class View
{
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var TemplateParser
     */
    private $templateParser;

    public function __construct(Configuration $configuration, TemplateParser $templateParser)
    {
        $this->configuration = $configuration;
        $this->templateParser = $templateParser;
    }

    public function parseTemplate(string $template, array $parameters = []): string
    {
        $templateFile = "{$this->configuration->getTemplateRootPath()}/{$template}";
        return $this->templateParser->parseTemplate($templateFile, $parameters);
    }
}