<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpVersion;

use InvalidArgumentException;

final class PhpVersion
{
    /** @var string */
    private $version;

    public function __construct(string $version)
    {
        if (preg_match('/^\d+\.\d+\.\d+$/', $version) !== 1) {
            throw new InvalidArgumentException("Invalid PHP version $version");
        }

        $this->version = $version;
    }

    public function __toString(): string
    {
        return $this->version;
    }
}
