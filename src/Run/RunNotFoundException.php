<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use PhpCsFixerPlayground\NotFoundException;
use Ramsey\Uuid\UuidInterface;

final class RunNotFoundException extends NotFoundException
{
    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self(
            sprintf('Cannot find run with UUID %s', $uuid->toString())
        );
    }
}
