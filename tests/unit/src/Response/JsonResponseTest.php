<?php

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonResponseTest
 * @package Response
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class JsonResponseTest extends TestCase
{
    /**
     * @var JsonResponse
     */
    private $response;

    /**
     *
     */
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
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGettingMimeType(): void
    {
        $expected = MimeType::TEXT_JSON;
        $actual = $this->response->getMimeType();
        $this->assertEquals($expected, $actual);
    }
    /* HELPERS */
}
