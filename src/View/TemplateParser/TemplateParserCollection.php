<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use HJerichen\Collections\Collection;
use HJerichen\Collections\ObjectCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @template-extends ObjectCollection<TemplateParser>
 * @template-extends Collection<TemplateParser>
 */
class TemplateParserCollection extends ObjectCollection
{
    public function __construct()
    {
        parent::__construct(TemplateParser::class);
    }
}