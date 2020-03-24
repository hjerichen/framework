<?php declare(strict_types=1);

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CustomResponseTest extends TestCase
{
    /**
     * @var CustomResponse
     */
    private $response;

    /**
     *
     */
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
        $this->assertInstanceOf($expected, $actual);
    }

    public function testGettingDefaultMimeType(): void
    {
        $expected = MimeType::TEXT_PlAIN;
        $actual = $this->response->getMimeType();
        $this->assertEquals($expected, $actual);
    }

    public function testSettingMimeType(): void
    {
        $this->response->setMimeType(MimeType::TEXT_HTML);

        $expected = MimeType::TEXT_HTML;
        $actual = $this->response->getMimeType();
        $this->assertEquals($expected, $actual);
    }

    /* HELPERS */
}
