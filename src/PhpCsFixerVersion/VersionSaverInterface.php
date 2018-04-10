<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

interface VersionSaverInterface
{
    public function save(string $version, string $zipUrl): void;
}
