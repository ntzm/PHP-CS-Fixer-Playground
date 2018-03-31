<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

interface ViewFactoryInterface
{
    public function make(
        string $code,
        array $fixers,
        string $result,
        array $appliedFixers,
        string $indent,
        string $lineEnding,
        string $generatedConfig
    ): string;
}
