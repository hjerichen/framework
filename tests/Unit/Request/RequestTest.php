<?php

namespace HJerichen\Framework\Test\Unit\Request;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\Request\Request;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class RequestTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new Request('/');
    }

    /* TESTS */

    public function testAddingMultipleArguments(): void
    {
        $this->request->addArgument('test1', 'value1');
        $this->request->addArguments(new MixedCollection(['test2' => 'value2', 'test3' => 'value3']));
        $this->request->addArguments(new MixedCollection(['test4' => 'value4', 'test5' => 'value5']));

        $expected = new MixedCollection();
        $expected['test1'] = 'value1';
        $expected['test2'] = 'value2';
        $expected['test3'] = 'value3';
        $expected['test4'] = 'value4';
        $expected['test5'] = 'value5';
        $actual = $this->request->getArguments();
        self::assertEquals($expected, $actual);
    }

    public function testGetBodyDefault(): void
    {
        $expected = '';
        $actual = $this->request->getBody();
        self::assertEquals($expected, $actual);
    }

    public function testGetBody(): void
    {
        $this->request = new Request('/jon', 'test');

        $expected = 'test';
        $actual = $this->request->getBody();
        self::assertEquals($expected, $actual);
    }
}
