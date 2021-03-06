<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Response;

use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\Response\TextResponse;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TextResponseTest extends TestCase
{
    private TextResponse $response;

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
        self::assertInstanceOf($expected, $actual);
    }

    public function testGettingMimeType(): void
    {
        $expected = MimeType::TEXT_PlAIN;
        $actual = $this->response->getMimeType();
        self::assertEquals($expected, $actual);
    }

    /* HELPERS */
}
