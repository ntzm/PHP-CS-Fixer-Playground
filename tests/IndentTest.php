<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use InvalidArgumentException;
use PhpCsFixerPlayground\Indent;
use PHPUnit\Framework\TestCase;

final class IndentTest extends TestCase
{
    /** @dataProvider provideValidIndents */
    public function testValid(string $input): void
    {
        $indent = new Indent($input);

        $this->assertSame($input, (string) $indent);
    }

    public function provideValidIndents(): array
    {
        return [
            ['    '],
            ['  '],
            ["\t"],
        ];
    }

    /** @dataProvider provideInvalidIndents */
    public function testInvalid(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid indent $input, must be one of \"    \", \"  \", \"\t\"");

        new Indent($input);
    }

    public function provideInvalidIndents(): array
    {
        return [
            ['foo'],
            [' '],
            ["\n"],
            ['     '],
            ['        '],
            ["\t\t"],
        ];
    }
}
