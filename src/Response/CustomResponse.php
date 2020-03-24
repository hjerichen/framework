<?php declare(strict_types=1);

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CustomResponse extends Response
{
    /**
     * @var string
     */
    private $mimeType = MimeType::TEXT_PlAIN;

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }
}