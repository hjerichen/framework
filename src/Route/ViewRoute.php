<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\View\ViewInitiator;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ViewRoute implements RouteInterface
{
    public function __construct(
        private readonly string $uri,
        private readonly string $template,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getInstantiatedClass(ObjectFactory $objectFactory): ViewInitiator
    {
        $predefinedArguments = ['template' => $this->template];
        return $objectFactory->instantiateClass(ViewInitiator::class, $predefinedArguments);
    }

    public function getMethod(): string
    {
        return 'execute';
    }
}