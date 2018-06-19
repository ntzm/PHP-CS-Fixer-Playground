<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpVersion;

interface PhpVersionFactoryInterface
{
    public function make(): PhpVersion;
}
