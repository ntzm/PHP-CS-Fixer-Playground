<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fixer;

interface FixerInterface
{
    public function fix(
        string $code,
        array $rules,
        string $indent,
        string $lineEnding
    ): FixReport;
}
