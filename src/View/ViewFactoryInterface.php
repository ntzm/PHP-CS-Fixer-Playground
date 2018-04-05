<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use PhpCsFixerPlayground\Entity\Run;

interface ViewFactoryInterface
{
    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        string $generatedConfig
    ): string;
}