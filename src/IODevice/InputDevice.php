<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice;

use HJerichen\Framework\Request\Request;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface InputDevice
{
    public function getRequest(): Request;
}