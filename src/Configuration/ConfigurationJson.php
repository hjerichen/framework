<?php declare(strict_types=1);

namespace HJerichen\Framework\Configuration;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ConfigurationJson implements Configuration
{
    /** @var array<string,mixed> */
    private array $configurationAsArray;

    public function __construct(
        private readonly string $configurationFile
    ) {
    }

    public function getTemplateEngine(): string
    {
        $this->loadConfigurationAsArray();
        return (string)($this->configurationAsArray['template-engine'] ?? 'default');
    }

    public function getTemplateRootPath(): string
    {
        $this->loadConfigurationAsArray();
        return (string)($this->configurationAsArray['template-root-path'] ?? '/application/tpl');
    }

    public function getCustomValue(string $key): mixed
    {
        $this->loadConfigurationAsArray();
        return $this->configurationAsArray[$key] ?? null;
    }

    private function loadConfigurationAsArray(): void
    {
        if (!isset($this->configurationAsArray)) {
            $fileContent = file_get_contents($this->configurationFile);
            /** @psalm-suppress MixedAssignment */
            $this->configurationAsArray = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}