<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use HJerichen\Framework\View\Exception\TemplateParserException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserSmart extends TemplateParser
{
    private array $parameters;

    public function __construct(
        private readonly TemplateParserCollection $templateParsers
    ) {
    }

    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        $this->templateFile = $templateFile;
        $this->parameters = $parameters;

        $this->throwExceptionIfFileNotFound();

        return $this->parseTemplateFromCollection();
    }

    private function parseTemplateFromCollection(): string
    {
        $lastException = null;

        foreach ($this->templateParsers as $templateParser) {
            try {
                return $templateParser->parseTemplate($this->templateFile, $this->parameters);
            } catch (TemplateParserException $exception) {
                $lastException = $exception;
                continue;
            }
        }

        throw $lastException;
    }
}