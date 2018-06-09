<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use PhpCsFixerPlayground\Entity\Run;
use Ramsey\Uuid\UuidInterface;

interface RunRepositoryInterface
{
    public function findByUuid(UuidInterface $uuid): Run;

    public function save(Run $run): void;
}
