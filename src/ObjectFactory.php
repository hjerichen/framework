<?php

namespace HJerichen\Framework;

use HJerichen\ClassInstantiator\ClassInstantiator;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\Framework\View\TemplateParser\TemplateParserDefault;
use HJerichen\Framework\View\TemplateParser\TemplateParserPhug;
use Phug\Renderer;

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
        $this->configuration = $configuration;
    }

    public function createTemplateParser(): TemplateParser
    {
        if ($this->configuration->getTemplateEngine() === 'phug') {
            return new TemplateParserPhug(new Renderer());
        }
        return new TemplateParserDefault();
    }
}