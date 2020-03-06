<?php

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TextResponseTest extends TestCase
{
    /**
     * @var TextResponse
     */
    private $response;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new TextResponse();
    }


    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = Response::class;
        $actual = $this->response;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGettingMimeType(): void
    {
        $expected = MimeType::TEXT_PlAIN;
        $actual = $this->response->getMimeType();
        $this->assertEquals($expected, $actual);
    }

    /* HELPERS */
}
