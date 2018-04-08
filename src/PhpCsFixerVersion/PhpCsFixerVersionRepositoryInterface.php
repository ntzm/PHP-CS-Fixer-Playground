<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

interface PhpCsFixerVersionRepositoryInterface
{
    public function has(string $version): bool;

    public function get(string $version): PhpCsFixerVersion;

    public function save(PhpCsFixerVersion $version): void;
}
