<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class ParseRulesFromRequest implements ParseRulesFromRequestInterface
{
    private const ENABLED = '_enabled';
    private const TRUE = '_true';
    private const FALSE = '_false';
    private const NULL = '_null';
    private const KEYS = '_keys';
    private const VALUES = '_values';

    private const TYPE_MAP = [
        self::TRUE => true,
        self::FALSE => false,
        self::NULL => null,
    ];

    public function __invoke(array $rules): array
    {
        $result = [];

        foreach ($rules as $name => $options) {
            if ($this->isDisabled($options)) {
                continue;
            }

            $options = $this->stripMetaData($options);

            $result[$name] = \count($options) === 0
                ? true
                : $this->parseOptions($options);
        }

        return $result;
    }

    private function isDisabled(array $options): bool
    {
        return $options[self::ENABLED] !== self::TRUE;
    }

    private function stripMetaData(array $options): array
    {
        unset($options[self::ENABLED]);

        return $options;
    }

    private function parseOptions(array $options): array
    {
        return array_map(function ($option) {
            return $this->parseOption($option);
        }, $options);
    }

    private function parseOption($option)
    {
        if (\is_array($option)) {
            return $this->parseArrayOption($option);
        }

        if (array_key_exists($option, self::TYPE_MAP)) {
            return self::TYPE_MAP[$option];
        }

        return $option;
    }

    private function parseArrayOption(array $option): array
    {
        if ($this->isAssociativeArrayOption($option)) {
            return $this->parseAssociativeArrayOption($option);
        }

        return $option;
    }

    private function isAssociativeArrayOption(array $option): bool
    {
        return array_keys($option) === [self::KEYS, self::VALUES];
    }

    private function parseAssociativeArrayOption(array $option): array
    {
        return array_combine($option[self::KEYS], $option[self::VALUES]);
    }
}
