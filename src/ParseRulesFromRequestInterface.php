<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

interface ParseRulesFromRequestInterface
{
    public function __invoke(array $rules): array;
}
