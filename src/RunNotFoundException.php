<?php

namespace PhpCsFixerPlayground;

use Exception;

final class RunNotFoundException extends Exception
{
    public static function fromId(string $id): self
    {
        return new self(
            sprintf('Cannot find run with ID %s', $id)
        );
    }
}
