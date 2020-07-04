<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Unit\Response;

use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class HtmlResponseTest extends TestCase
{
    /**
     * @var HtmlResponse
     */
    private $response;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new HtmlResponse();
    }


    /* TESTS */

    public function testImplementsCorrectInterface(): void
    {
        $expected = Response::class;
        $actual = $this->response;
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGettingMimeType(): void
    {
        $expected = MimeType::TEXT_HTML;
        $actual = $this->response->getMimeType();
        $this->assertEquals($expected, $actual);
    }


    /* HELPERS */
}
