<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

interface RunRepositoryInterface
{
    public function getByHash(string $hash): Run;

    public function save(Run $run): Run;
}
