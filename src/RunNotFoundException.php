<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Exception;

final class RunNotFoundException extends Exception
{
    public static function fromHash(string $hash): self
    {
        return new self(
            sprintf('Cannot find run with ID %s', $hash)
        );
    }
}
