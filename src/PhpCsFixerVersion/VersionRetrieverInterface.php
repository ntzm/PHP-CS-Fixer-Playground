<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

interface VersionRetrieverInterface
{
    public function retrieve(): array;
}
