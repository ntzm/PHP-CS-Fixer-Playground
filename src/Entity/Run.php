<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Run
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $code;

    /**
     * @var array<string, bool>
     * @ORM\Column(type="json")
     */
    private $rules;

    /**
     * @var string
     * @ORM\Column(type="string", length=4)
     */
    private $indent;

    /**
     * @var string
     * @ORM\Column(type="string", length=4)
     */
    private $lineEnding;

    public function __construct(
        string $code,
        array $rules,
        string $indent,
        string $lineEnding
    ) {
        $this->code = $code;
        $this->rules = $rules;
        $this->indent = $indent;
        $this->lineEnding = $lineEnding;
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
