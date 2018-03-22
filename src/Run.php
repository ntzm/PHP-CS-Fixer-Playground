<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class Run
{
    private $id;

    private $code;

    private $result;

    private $rules;

    public function __construct(
        string $id,
        string $code,
        string $result,
        array $rules
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->result = $result;
        $this->rules = $rules;
    }

    public function getId(): string
    {
        return $this->id;
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
}
