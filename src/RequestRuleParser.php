<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class RequestRuleParser implements RequestRuleParserInterface
{
    private const ENABLED = '_enabled';
    private const TRUE = '_true';
    private const FALSE = '_false';
    private const NULL = '_null';

    public function parse(array $rules): array
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
            if ($option === self::TRUE) {
                $option = true;
            } elseif ($option === self::FALSE) {
                $option = false;
            } elseif ($option === self::NULL) {
                $option = null;
            } elseif (strpos($option, "\r\n") !== false) {
                $option = explode("\r\n", $option);
            }
        }

        return $options;
    }
}
