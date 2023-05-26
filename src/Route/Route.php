<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;

/** @template T of object */
class Route implements RouteInterface
{
    /**
     * @param class-string<T> $class
     */
    public function __construct(
        private readonly string $uri,
        private readonly string $class,
        private readonly string $method,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /** @return T */
    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        return $objectFactory->instantiateClass($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}