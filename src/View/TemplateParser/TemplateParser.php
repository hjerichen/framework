<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use HJerichen\Framework\View\Exception\TemplateParserException;
use SplFileInfo;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
abstract class TemplateParser
{
    protected string $templateFile;

    abstract public function parseTemplate(string $templateFile, array $parameters = []): string;

    protected function throwExceptionIfNotFileWithExtension(string $extension): void
    {
        if ($this->getExtensionOfTemplateFile() !== $extension) {
            $message = "Template file not supported: $this->templateFile";
            throw new TemplateParserException($message);
        }
    }

    protected function throwExceptionIfFileNotFound(): void
    {
        if (!file_exists($this->templateFile)) {
            $message = "Template file not found: $this->templateFile";
            throw new TemplateParserException($message);
        }
    }

    private function getExtensionOfTemplateFile(): string
    {
        $file = new SplFileInfo($this->templateFile);
        return $file->getExtension();
    }
}