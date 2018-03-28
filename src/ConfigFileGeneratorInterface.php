<?php

namespace PhpCsFixerPlayground;

interface ConfigFileGeneratorInterface
{
    public function generate(
        array $rules,
        string $indent,
        string $lineEnding
    ): string;
}
