<?php

namespace PhpCsFixerPlayground;

interface ViewFactoryInterface
{
    public function make(string $code, array $fixers, string $result): string;
}
