<?php

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class JsonResponse extends Response
{
    public function getMimeType(): string
    {
        return MimeType::TEXT_JSON;
    }
}