<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice;

use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface OutputDevice
{
    public function outputResponse(Response $response): void;
}