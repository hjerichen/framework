<?php

namespace HJerichen\Framework\Response\Exception;

use Exception;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ResponseException extends Exception
{
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}