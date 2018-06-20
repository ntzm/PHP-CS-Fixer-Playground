<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use InvalidArgumentException;

final class PhpCsFixerVersion
{
    /** @var string */
    private $number;

    /** @var string */
    private $name;

    public function __construct(string $number, string $name)
    {
        if (preg_match('/^\d+\.\d+\.\d+$/', $number) !== 1) {
            throw new InvalidArgumentException("Invalid PHP-CS-Fixer version $number");
        }

        $this->number = $number;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->number.' '.$this->name;
    }
}
