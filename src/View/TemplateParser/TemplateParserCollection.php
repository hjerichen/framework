<?php declare(strict_types=1);

namespace HJerichen\Framework\View\TemplateParser;

use HJerichen\Collections\ObjectCollection;
use Traversable;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TemplateParserCollection extends ObjectCollection
{
    public function __construct()
    {
        parent::__construct(TemplateParser::class);
    }

    /**
     * @return Traversable | TemplateParser[]
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection SenselessProxyMethodInspection
     */
    public function getIterator(): Traversable
    {
        return parent::getIterator();
    }
}