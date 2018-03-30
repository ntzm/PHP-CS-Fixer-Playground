<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

interface RequestRuleParserInterface
{
    public function parse(array $rules): array;
}
