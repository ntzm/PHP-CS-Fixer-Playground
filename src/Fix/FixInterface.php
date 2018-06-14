<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fix;

use PhpCsFixerPlayground\LineEnding;

interface FixInterface
{
    public function __invoke(
        string $code,
        array $rules,
        string $indent,
        LineEnding $lineEnding
    ): FixReport;
}
