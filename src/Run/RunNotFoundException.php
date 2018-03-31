<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use Exception;

final class RunNotFoundException extends Exception
{
    public static function fromHash(string $hash): self
    {
        return new self(
            sprintf('Cannot find run with hash %s', $hash)
        );
    }
}
