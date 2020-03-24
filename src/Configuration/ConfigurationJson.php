<?php declare(strict_types=1);

namespace HJerichen\Framework\Configuration;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ConfigurationJson implements Configuration
{
    /**
     * @var string
     */
    private $configurationFile;
    /**
     * @var array<string,mixed>
     */
    private $configurationAsArray;

    public function __construct(string $configurationFile)
    {
        $this->configurationFile = $configurationFile;
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

    private function loadConfigurationAsArray(): void
    {
        if ($this->configurationAsArray === null) {
            $this->configurationAsArray = json_decode(file_get_contents($this->configurationFile), true);
        }
    }
}