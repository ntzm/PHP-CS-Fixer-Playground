<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class Run
{
    private $code;

    private $rules;

    private $hash;

    public function __construct(
        string $code,
        array $rules,
        string $hash = null
    ) {
        $this->code = $code;
        $this->rules = $rules;
        $this->hash = $hash;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}
