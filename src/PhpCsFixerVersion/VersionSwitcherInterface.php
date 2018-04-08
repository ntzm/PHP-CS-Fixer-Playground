<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

interface VersionSwitcherInterface
{
    public function switchTo(PhpCsFixerVersion $version): void;
}
