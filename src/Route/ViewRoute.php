<?php declare(strict_types=1);

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\View\ViewInitiator;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ViewRoute implements RouteInterface
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $template;

    public function __construct(string $uri, string $template)
    {
        $this->uri = $uri;
        $this->template = $template;
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