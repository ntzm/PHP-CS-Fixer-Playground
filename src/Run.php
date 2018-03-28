<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class Run
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var array<string, bool>
     */
    private $rules;

    /**
     * @var string
     */
    private $indent;

    /**
     * @var string
     */
    private $lineEnding;

    /**
     * @var string|null
     */
    private $hash;

    public function __construct(
        string $code,
        array $rules,
        string $indent,
        string $lineEnding,
        string $hash = null
    ) {
        $this->code = $code;
        $this->rules = $rules;
        $this->indent = $indent;
        $this->lineEnding = $lineEnding;
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

    public function getIndent(): string
    {
        return $this->indent;
    }

    public function getLineEnding(): string
    {
        return $this->lineEnding;
    }

    public function getRealLineEnding(): string
    {
        if ($this->lineEnding === '\n') {
            return "\n";
        }

        if ($this->lineEnding === '\r\n') {
            return "\r\n";
        }

        return $this->lineEnding;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}
