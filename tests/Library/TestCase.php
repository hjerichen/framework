<?php declare(strict_types=1);

namespace HJerichen\Framework\Test\Library;

use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\View\TemplateParser\TemplateParser;
use HJerichen\ProphecyPHP\PHPProphetTrait;
use Phug\Renderer\Profiler\Profile;
use ReflectionClass;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    use PHPProphetTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preparePHPFunctions();
    }

    protected function getNamespaceFoClass(string $class): string
    {
        $reflection = new ReflectionClass($class);
        return $reflection->getNamespaceName();
    }

    private function preparePHPFunctions(): void
    {
        $php = $this->prophesizePHP($this->getNamespaceFoClass(Profile::class));
        $php->prepare('memory_get_usage', 'microtime');

        $php = $this->prophesizePHP($this->getNamespaceFoClass(ObjectFactory::class));
        $php->prepare('class_exists');

        $php = $this->prophesizePHP($this->getNamespaceFoClass(TemplateParser::class));
        $php->prepare('file_get_contents', 'file_exists');
    }
}