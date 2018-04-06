<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fixer;

use PhpCsFixerPlayground\LineEnding;

interface FixerInterface
{
    public function fix(
        string $code,
        array $rules,
        string $indent,
        LineEnding $lineEnding
    ): FixReport;
}
