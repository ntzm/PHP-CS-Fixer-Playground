<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

interface FixerInterface
{
    public function fix(string $code, array $rules): string;
}
