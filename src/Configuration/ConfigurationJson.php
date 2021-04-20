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
        private string $configurationFile
    ) {
    }

    public function getTemplateEngine(): string
    {
        $this->loadConfigurationAsArray();
        return $this->configurationAsArray['template-engine'] ?? 'default';
    }

    public function getTemplateRootPath(): string
    {
        $this->loadConfigurationAsArray();
        return $this->configurationAsArray['template-root-path'] ?? '/application/tpl';
    }

    public function getCustomValue(string $key): string|null
    {
        $this->loadConfigurationAsArray();
        return $this->configurationAsArray[$key] ?? null;
    }

    private function loadConfigurationAsArray(): void
    {
        if (!isset($this->configurationAsArray)) {
            $fileContent = file_get_contents($this->configurationFile);
            $this->configurationAsArray = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}