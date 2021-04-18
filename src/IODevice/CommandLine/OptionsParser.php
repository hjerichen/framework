<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\CommandLine;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class OptionsParser
{
    /** @var string[] */
    private array $parsedOptions = [];

    /**
     * @param  string[] $argv
     * @return string[]
     */
    public function parse(array $argv = []): array
    {
        array_shift($argv);
        $this->parsedOptions = [];
        $this->parseOptions($argv);

        return $this->parsedOptions;
    }

    private function parseOptions(array $argv): void
    {
        $argumentCount = count($argv);

        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $argumentCount; $i++) {
            $argument = $argv[$i];
            $argumentNext = $argv[$i + 1] ?? null;

            if (str_starts_with($argument, '--')) {
                if ($this->parseOptionWithEqualSign($argument)) {
                    continue;
                }

                $key = $this->stripHyphens($argument);
                if ($argumentNext !== null && $argumentNext[0] !== '-') {
                    $this->parsedOptions[$key] = $argumentNext;
                    $i++;
                    continue;
                }
                $this->parsedOptions[$key] = $this->parsedOptions[$key] ?? true;
                continue;
            }

            if (str_starts_with($argument, '-')) {
                if ($this->parseOptionWithEqualSign($argument)) {
                    continue;
                }

                // -a value1 / -abc value2 / -abc
                $hasNextElementDash = !($argumentNext !== null && $argumentNext[0] !== '-');
                foreach (str_split(substr($argument, 1)) as $char) {
                    $this->parsedOptions[$char] = $hasNextElementDash ? true : $argumentNext;
                }

                if (!$hasNextElementDash) {
                    $i++;
                }
                continue;
            }

            $this->parsedOptions[] = $argument;
        }
    }

    private function parseOptionWithEqualSign(string $option): bool
    {
        $equalPosition = strpos($option, '=');
        if ($equalPosition === false) return false;

        $this->parsedOptions = array_merge($this->parsedOptions, $this->getOptionWithEqualSign($option, $equalPosition));
        return true;
    }

    /**
     * @param string $argument
     * @param int    $equalPosition
     * @return array<string,string>
     */
    private function getOptionWithEqualSign(string $argument, int $equalPosition): array
    {
        $name = substr($argument, 0, $equalPosition);
        $value = substr($argument, $equalPosition + 1);

        $name = $this->stripHyphens($name);
        $option[$name] = $value;

        return $option;
    }

    private function stripHyphens(string $argument): string
    {
        if (!str_starts_with($argument, '-')) {
            return $argument;
        }
        $argument = substr($argument, 1);
        return $this->stripHyphens($argument);
    }
}