<?php declare(strict_types=1);

namespace HJerichen\Framework\Response\Exception;

use HJerichen\Framework\Request\Request;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class UnknownRouteException extends ResponseException
{
    public function __construct(Request $request)
    {
        $message = "No route found for URI: {$request->getUri()}";
        parent::__construct($message, 404);
    }
}