<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpVersion;

final class PhpVersionFactory implements PhpVersionFactoryInterface
{
    public function make(): PhpVersion
    {
        return new PhpVersion(PHP_VERSION);
    }
}
