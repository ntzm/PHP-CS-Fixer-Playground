<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

interface ViewFactoryInterface
{
    public function make(
        string $code,
        array $fixers,
        string $result,
        string $indent,
        string $lineEnding,
        string $generatedConfig
    ): string;
}
