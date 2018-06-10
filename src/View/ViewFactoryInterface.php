<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Issue;

interface ViewFactoryInterface
{
    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        array $deprecationMessages,
        string $generatedConfig,
        Issue $issue = null
    ): string;
}
