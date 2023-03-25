<?php declare(strict_types=1);

namespace HJerichen\Framework\Request;

use HJerichen\Collections\MixedCollection;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Request
{
    private MixedCollection $arguments;

    public function __construct(
        private readonly string $uri,
        private readonly string $body = ''
    ) {
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

    public function addArgument(string $name, mixed $value): void
    {
        $this->arguments[$name] = $value;
    }

    public function addArguments(MixedCollection $arguments): void
    {
        $this->arguments->merge($arguments);
    }

    public function getBody(): string
    {
        return $this->body;
    }
}