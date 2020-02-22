<?php

namespace HJerichen\Framework\IODevice\CommandLine;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface ArgumentParser
{
    /**
     * @return array<string>
     */
    public function getPlainArguments(): array;

    /**
     * @return array<string,mixed>
     */
    public function getNamedArguments(): array;
}