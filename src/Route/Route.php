<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;

class Route implements RouteInterface
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

    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        return $objectFactory->instantiateClass($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}