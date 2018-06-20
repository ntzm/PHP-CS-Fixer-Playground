<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

interface PhpCsFixerVersionFactoryInterface
{
    public function make(): PhpCsFixerVersion;
}
