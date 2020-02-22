<?php

namespace HJerichen\Framework\Request;

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
     * @var array<string,mixed>
     */
    private $arguments = [];

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array<string,mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addArgument(string $name, $value): void
    {
        $this->arguments[$name] = $value;
    }

    /**
     * @param array<string,mixed> $arguments
     */
    public function addArguments(array $arguments): void
    {
        $this->arguments = array_merge($this->arguments, $arguments);
    }
}