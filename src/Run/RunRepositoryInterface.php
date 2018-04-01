<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use PhpCsFixerPlayground\Entity\Run;

interface RunRepositoryInterface
{
    public function getByHash(string $hash): Run;

    public function save(Run $run): void;
}
