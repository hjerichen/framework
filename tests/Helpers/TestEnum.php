<?php /** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

namespace HJerichen\Framework\Test\Helpers;

use HJerichen\Framework\Types\Enum;

/**
 * @method static TEST1()
 * @method static TEST2()
 * @template-extends Enum<string>
 * @psalm-immutable
 */
class TestEnum extends Enum
{
    private const TEST1 = 'test1';
    private const TEST2 = 'test2';
}
