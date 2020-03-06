<?php


namespace HJerichen\Framework\View\TemplateParser;


/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserDefault implements TemplateParser
{
    public function parseTemplate(string $templateFile, array $parameters = []): string
    {
        $output = $this->getOutputForTemplateFile($templateFile);
        $output = $this->appendParametersToOutput($output, $parameters);
        return $output;
    }

    private function getOutputForTemplateFile(string $templateFile): string
    {
        return "template-file: {$templateFile}.tpl";
    }

    private function appendParametersToOutput(string $output, array $parameters): string
    {
        if (count($parameters) === 0) return $output;

        $parametersAsString = var_export($parameters, true);
        return $output . "\n{$parametersAsString}";
    }
}