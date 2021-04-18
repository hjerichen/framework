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
        private string $uri,
        private string $template,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        $predefinedArguments = ['template' => $this->template];
        return $objectFactory->instantiateClass(ViewInitiator::class, $predefinedArguments);
    }

    public function getMethod(): string
    {
        return 'execute';
    }
}