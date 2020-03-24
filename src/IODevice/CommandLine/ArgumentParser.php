<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Collections\Primitive\StringCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface ArgumentParser
{
    public function getPlainArguments(): StringCollection;

    public function getNamedArguments(): MixedCollection;
}