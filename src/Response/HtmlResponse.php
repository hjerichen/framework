<?php declare(strict_types=1);

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class HtmlResponse extends Response
{
    public function getMimeType(): string
    {
        return MimeType::TEXT_HTML;
    }
}