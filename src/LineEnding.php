<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use InvalidArgumentException;

final class LineEnding
{
    private const MAP = [
        '\n' => "\n",
        '\r\n' => "\r\n",
    ];

    /** @var string */
    private $realLineEnding;

    public function __construct(string $realLineEnding)
    {
        if (!\in_array($realLineEnding, self::MAP, true)) {
            throw self::invalidLineEnding($realLineEnding);
        }

        $this->realLineEnding = $realLineEnding;
    }

    public static function fromVisible(string $visibleLineEnding): self
    {
        if (!\array_key_exists($visibleLineEnding, self::MAP)) {
            throw self::invalidLineEnding($visibleLineEnding);
        }

        return new self(self::MAP[$visibleLineEnding]);
    }

    public function getVisible(): string
    {
        return array_flip(self::MAP)[$this->realLineEnding];
    }

    public function getReal(): string
    {
        return $this->realLineEnding;
    }

    public function __toString(): string
    {
        return $this->realLineEnding;
    }

    private static function invalidLineEnding(string $lineEnding): InvalidArgumentException
    {
        return new InvalidArgumentException(
            sprintf(
                'Invalid line ending %s, must be one of %s',
                $lineEnding,
                implode(', ', array_keys(self::MAP)),
            ),
        );
    }
}
