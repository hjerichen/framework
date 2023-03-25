<?php declare(strict_types=1);

namespace HJerichen\Framework\Types;

/**
 * @template T
 * @template-extends \MyCLabs\Enum\Enum<T>
 * @psalm-immutable
 */
class Enum extends \MyCLabs\Enum\Enum
{
    /** @noinspection PhpRedundantMethodOverrideInspection */
    public function __toString(): string
    {
        return parent::__toString();
    }
}
