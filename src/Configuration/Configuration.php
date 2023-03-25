<?php declare(strict_types=1);

namespace HJerichen\Framework\Configuration;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
interface Configuration
{
    public function getTemplateEngine(): string;
    public function getTemplateRootPath(): string;
    public function getCustomValue(string $key): mixed;
}