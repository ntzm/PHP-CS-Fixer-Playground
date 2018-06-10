<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Issue;

interface ViewFactoryInterface
{
    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        array $deprecationMessages,
        ConfigFile $configFile,
        Issue $issue = null
    ): string;
}
