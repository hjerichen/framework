<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;

class Route implements RouteInterface
{
    public function __construct(
        private string $uri,
        private string $class,
        private string $method,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        return $objectFactory->instantiateClass($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}