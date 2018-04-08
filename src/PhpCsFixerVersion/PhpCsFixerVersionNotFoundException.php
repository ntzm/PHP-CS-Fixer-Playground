<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\NotFoundException;

final class PhpCsFixerVersionNotFoundException extends NotFoundException
{
    public static function fromVersion(string $version): self
    {
        return new self(
            sprintf('Cannot find version %s', $version)
        );
    }
}
