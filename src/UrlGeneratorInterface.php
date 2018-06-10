<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixerPlayground\Entity\Run;

interface UrlGeneratorInterface
{
    public function generateUrlForRun(Run $run): string;
}
