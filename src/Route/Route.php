<?php

namespace HJerichen\Framework\Route;

class Route
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $method;

    public function __construct(string $uri, string $class, string $method)
    {
        $this->uri = $uri;
        $this->class = $class;
        $this->method = $method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}