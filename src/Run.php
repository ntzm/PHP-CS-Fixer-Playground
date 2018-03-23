<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class Run
{
    private $code;

    private $result;

    private $rules;

    private $hash;

    public function __construct(
        string $code,
        string $result,
        array $rules,
        string $hash = null
    ) {
        $this->code = $code;
        $this->result = $result;
        $this->rules = $rules;
        $this->hash = $hash;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getResult(): string
    {
        return $this->result;
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
