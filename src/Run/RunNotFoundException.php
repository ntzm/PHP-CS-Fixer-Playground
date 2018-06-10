<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use PhpCsFixerPlayground\NotFoundException;
use Ramsey\Uuid\UuidInterface;

final class RunNotFoundException extends NotFoundException
{
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self("Cannot find run with UUID {$uuid->toString()}");
    }
}
