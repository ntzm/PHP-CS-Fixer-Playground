<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use InvalidArgumentException;

final class Indent
{
    private const ALLOWED_INDENTS = [
        '    ',
        '  ',
        "\t",
    ];

    /** @var string */
    private $indent;

    public function __construct(string $indent)
    {
        if (!\in_array($indent, self::ALLOWED_INDENTS, true)) {
            throw self::invalidIndent($indent);
        }

        $this->indent = $indent;
    }

    private static function invalidIndent(string $indent): InvalidArgumentException
    {
        return new InvalidArgumentException(
            sprintf(
                'Invalid indent %s, must be one of "%s"',
                $indent,
                implode('", "', self::ALLOWED_INDENTS),
            ),
        );
    }

    public function __toString(): string
    {
        return $this->indent;
    }
}
