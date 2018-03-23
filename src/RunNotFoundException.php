<?php

namespace PhpCsFixerPlayground;

use Exception;

final class RunNotFoundException extends Exception
{
    public static function fromId(int $id): self
    {
        return new self(
            sprintf('Cannot find run with ID %d', $id)
        );
    }
}
