<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

interface PhpCsFixerVersionFactoryInterface
{
    public function make(): PhpCsFixerVersion;
}
