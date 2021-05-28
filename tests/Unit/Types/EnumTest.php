<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Types;

use HJerichen\Framework\Test\Helpers\TestEnum;
use HJerichen\Framework\Test\Library\TestCase;

class EnumTest extends TestCase
{
    public function testUsage(): void
    {
        self::assertInstanceOf(TestEnum::class, TestEnum::TEST1());
        self::assertEquals(TestEnum::TEST1(), TestEnum::TEST1());
        self::assertEquals(TestEnum::TEST1(), TestEnum::from('test1'));
        self::assertEquals('test1', TestEnum::TEST1());
        self::assertEquals(['TEST1' => 'test1', 'TEST2' => 'test2'], TestEnum::toArray());
        self::assertSame('test1', (string)TestEnum::TEST1());
        self::assertTrue(TestEnum::isValid('test1'));
    }
}
