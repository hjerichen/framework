<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface TemplateParser
{
    public function parseTemplate(string $templateFile, array $parameters = []): string;
}