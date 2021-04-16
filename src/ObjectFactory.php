<?php declare(strict_types=1);

namespace HJerichen\Framework;

use HJerichen\ClassInstantiator\ClassInstantiator;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\View\TemplateParser\DecoratorToAppendFileExtension;
use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\Framework\View\TemplateParser\TemplateParserCollection;
use HJerichen\Framework\View\TemplateParser\TemplateParserHtml;
use HJerichen\Framework\View\TemplateParser\TemplateParserSimpleOutput;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use HJerichen\Framework\View\TemplateParser\TemplateParserSmart;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ObjectFactory extends ClassInstantiator
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        parent::__construct();
        $this->configuration = $configuration;
    }

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    public function createTemplateParser(): TemplateParser
    {
        switch ($this->configuration->getTemplateEngine()) {
            case 'simple-output':
                $templateParser = new TemplateParserSimpleOutput();
                break;
            case 'phug':
                $templateParser = new TemplateParserPhug(new \Phug\Renderer());
                break;
            default:
                $templateParser = $this->createSmartTemplateParser();
                break;
        }
        return new DecoratorToAppendFileExtension($templateParser);
    }

    /** @noinspection PhpFullyQualifiedNameUsageInspection */
    private function createSmartTemplateParser(): TemplateParserSmart
    {
        $templateParserCollection = new TemplateParserCollection();
        $templateParserCollection[] = new TemplateParserHtml();
        if (class_exists(\Phug\Renderer::class)) {
            $templateParserCollection[] = new TemplateParserPhug(new \Phug\Renderer());
        }
        return new TemplateParserSmart($templateParserCollection);
    }
}