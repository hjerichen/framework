<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Response;

use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Response\CustomResponse;
use HJerichen\Framework\Response\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CustomResponseTest extends TestCase
{
    private CustomResponse $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new CustomResponse();
    }

    /* TESTS */

    public function testClassImplementsCorrectInterface(): void
    {
        $expected = Response::class;
        $actual = $this->response;
        self::assertInstanceOf($expected, $actual);
    }

    public function testGettingDefaultMimeType(): void
    {
        $expected = MimeType::TEXT_PlAIN;
        $actual = $this->response->getMimeType();
        self::assertEquals($expected, $actual);
    }

    public function testSettingMimeType(): void
    {
        $this->response->setMimeType(MimeType::TEXT_HTML);

        $expected = MimeType::TEXT_HTML;
        $actual = $this->response->getMimeType();
        self::assertEquals($expected, $actual);
    }
}
