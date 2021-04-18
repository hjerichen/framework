<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Response;

use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Response\JsonResponse;
use HJerichen\Framework\Response\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class JsonResponseTest extends TestCase
{
    private JsonResponse $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new JsonResponse();
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
        $expected = MimeType::TEXT_JSON;
        $actual = $this->response->getMimeType();
        self::assertEquals($expected, $actual);
    }
}
