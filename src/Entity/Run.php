<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="runs")
 */
final class Run
{
    private const MAX_CODE_SIZE = 50000;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $code;

    /**
     * @var array
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
     * @ORM\Column(name="line_ending", type="string", length=2)
     */
    private $lineEnding;

    public function __construct(
        string $code,
        array $rules,
        Indent $indent,
        LineEnding $lineEnding
    ) {
        if (\strlen($code) > self::MAX_CODE_SIZE) {
            throw new InvalidArgumentException(
                'Code is over '.self::MAX_CODE_SIZE.' characters long'
            );
        }

        $this->id = Uuid::uuid4()->toString();
        $this->code = $code;
        $this->rules = $rules;
        $this->indent = (string) $indent;
        $this->lineEnding = $lineEnding->getReal();
    }

    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getIndent(): Indent
    {
        return new Indent($this->indent);
    }

    public function getLineEnding(): LineEnding
    {
        return new LineEnding($this->lineEnding);
    }

    public function getConfigFile(): ConfigFile
    {
        return new ConfigFile(
            $this->getRules(),
            $this->getIndent(),
            $this->getLineEnding()
        );
    }
}
