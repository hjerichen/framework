<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Collections\Primitive\StringCollection;
use Phalcon\Cop\Parser;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ArgumentParserPhalconCop implements ArgumentParser
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new Parser();
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
        return array_filter($arguments, static function($key) {
            return is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function extractNamedArguments(array $arguments): array
    {
        return array_filter($arguments, static function($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}