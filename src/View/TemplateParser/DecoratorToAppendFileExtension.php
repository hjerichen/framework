<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use DirectoryIterator;
use SplFileInfo;

class DecoratorToAppendFileExtension extends TemplateParser
{
    public function __construct(
        private TemplateParser $templateParser
    ) {
    }

    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        $templateFile = $this->appendExtensionToTemplateFile($templateFile);
        return $this->templateParser->parseTemplate($templateFile, $parameters);
    }

    private function appendExtensionToTemplateFile(string $templateFileName): string
    {
        $templateFile = new SplFileInfo($templateFileName);
        if (!is_dir($templateFile->getPath())) {
            return $templateFileName;
        }

        foreach (new DirectoryIterator($templateFile->getPath()) as $file) {
            if ($this->fileMatchesTemplateFile($file, $templateFile)) {
                return $file->getPathname();
            }
        }
        return $templateFileName;
    }

    private function fileMatchesTemplateFile(SplFileInfo $file, SplFileInfo $templateFile): bool
    {
        $fileBasename = $file->getBasename(".{$file->getExtension()}");
        return $file->isFile() && $fileBasename === $templateFile->getBasename();
    }
}