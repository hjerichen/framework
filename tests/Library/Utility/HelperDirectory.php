<?php

namespace HJerichen\Framework\Test\Library\Utility;

use ReflectionClass;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class HelperDirectory
{
    private string $subFolder;

    public function __construct(
        private object $object,
        string $subFolder = ''
    ) {
        $this->subFolder = trim($subFolder);
    }

    public function __toString(): string
    {
        return $this->buildDirectory();
    }

    private function buildDirectory(): string
    {
        $directory = $this->buildBaseDirectory();
        if ($this->subFolder !== '') {
            $directory .= "/$this->subFolder";
        }
        return $directory;
    }

    private function buildBaseDirectory(): string
    {
        $reflection = new ReflectionClass($this->object);

        $directory = dirname($reflection->getFileName());
        $className = $reflection->getShortName();

        return sprintf('%s/_%s_', $directory, $className);
    }
}