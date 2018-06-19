<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fix;

use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;

interface FixInterface
{
    public function __invoke(
        string $code,
        array $rules,
        Indent $indent,
        LineEnding $lineEnding
    ): FixReport;
}
