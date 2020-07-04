<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserHtml extends TemplateParser
{
    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        $this->templateFile = $templateFile;

        $this->throwExceptionIfNotFileWithExtension('html');
        $this->throwExceptionIfFileNotFound();

        return $this->getContentOfTemplateFile();
    }

    private function getContentOfTemplateFile(): string
    {
        return file_get_contents($this->templateFile);
    }
}