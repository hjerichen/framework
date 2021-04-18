<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Collections\Primitive\StringCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ArgumentParserSimple implements ArgumentParser
{
    private OptionsParser $parser;

    public function __construct()
    {
        $this->parser = new OptionsParser();
    }

    public function getPlainArguments(): StringCollection
    {
        global $argv;
        $arguments = $this->parser->parse($argv);
        $argumentsPlain = $this->extractPlainArguments($arguments);
        return new StringCollection($argumentsPlain);
    }

    public function getNamedArguments(): MixedCollection
    {
        global $argv;
        $arguments = $this->parser->parse($argv);
        $arguments = $this->extractNamedArguments($arguments);
        return new MixedCollection($arguments);
    }

    private function extractPlainArguments(array $arguments): array
    {
        return array_filter($arguments, static fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
    }

    private function extractNamedArguments(array $arguments): array
    {
        return array_filter($arguments, static fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
    }
}