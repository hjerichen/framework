<?php

namespace HJerichen\Framework\IODevice\CommandLine;

use HJerichen\Collections\Primitive\StringCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface ArgumentParser
{
    public function getPlainArguments(): StringCollection;

    /**
     * @return array<string,mixed>
     */
    public function getNamedArguments(): array;
}