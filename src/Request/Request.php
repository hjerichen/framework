<?php

namespace HJerichen\Framework\Request;

use HJerichen\Collections\MixedCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Request
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var MixedCollection
     */
    private $arguments;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
        $this->arguments = new MixedCollection();
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getArguments(): MixedCollection
    {
        return $this->arguments;
    }

    public function addArgument(string $name, $value): void
    {
        $this->arguments[$name] = $value;
    }

    public function addArguments(MixedCollection $arguments): void
    {
        $mergedArguments = array_merge($this->arguments->asArray(), $arguments->asArray());
        $this->arguments = new MixedCollection($mergedArguments);
    }
}