<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class ParseRulesFromRequest implements ParseRulesFromRequestInterface
{
    private const ENABLED = '_enabled';
    private const TRUE = '_true';
    private const FALSE = '_false';
    private const NULL = '_null';

    private const TYPE_MAP = [
        self::TRUE => true,
        self::FALSE => false,
        self::NULL => null,
    ];

    public function __invoke(array $rules): array
    {
        $result = [];

        foreach ($rules as $name => $options) {
            if ($options[self::ENABLED] !== self::TRUE) {
                continue;
            }

            unset($options[self::ENABLED]);

            if (empty($options)) {
                $result[$name] = true;
            } else {
                $result[$name] = $this->parseOptions($options);
            }
        }

        return $result;
    }

    private function parseOptions(array $options): array
    {
        foreach ($options as &$option) {
            if (\is_array($option)) {
                continue;
            }

            if (array_key_exists($option, self::TYPE_MAP)) {
                $option = self::TYPE_MAP[$option];
            } elseif (strpos($option, "\r\n") !== false) {
                $option = explode("\r\n", $option);
            }
        }

        return $options;
    }
}
