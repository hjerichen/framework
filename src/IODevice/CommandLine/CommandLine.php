<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\CommandLine;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class CommandLine implements IODevice
{
    /**
     * @var ArgumentParser
     */
    private $argumentParser;

    public function __construct()
    {
        $this->argumentParser = new ArgumentParserSimple();
    }

    public function getRequest(): Request
    {
        $uri = $this->buildUri();
        $arguments = $this->buildArguments();

        $request = new Request($uri);
        $request->addArguments($arguments);
        return $request;
    }

    private function buildUri(): string
    {
        $plainArguments = $this->argumentParser->getPlainArguments();
        return '/' . implode('/', $plainArguments->asArray());
    }

    private function buildArguments(): MixedCollection
    {
        return $this->argumentParser->getNamedArguments();
    }

    public function outputResponse(Response $response): void
    {
        fwrite(STDOUT, $response->getContent());
        if ($response->getException())
            fwrite(STDERR, $response->getException()->getMessage());
    }
}