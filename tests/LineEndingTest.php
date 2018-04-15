<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use InvalidArgumentException;
use PhpCsFixerPlayground\LineEnding;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\LineEnding
 */
final class LineEndingTest extends TestCase
{
    public function testGetVisible(): void
    {
        $this->assertSame('\n', (new LineEnding("\n"))->getVisible());
        $this->assertSame('\r\n', (new LineEnding("\r\n"))->getVisible());
    }

    public function testGetReal(): void
    {
        $this->assertSame("\n", (new LineEnding("\n"))->getReal());
        $this->assertSame("\r\n", (new LineEnding("\r\n"))->getReal());
    }

    public function testFromVisible(): void
    {
        $this->assertSame("\n", LineEnding::fromVisible('\n')->getReal());
        $this->assertSame("\r\n", LineEnding::fromVisible('\r\n')->getReal());
    }

    public function testToString(): void
    {
        $this->assertSame("\n", (string) new LineEnding("\n"));
        $this->assertSame("\r\n", (string) new LineEnding("\r\n"));
    }

    public function testConstructInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid line ending foo, must be one of \n, \r\n');

        new LineEnding('foo');
    }

    public function testFromVisibleInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid line ending foo, must be one of \n, \r\n');

        LineEnding::fromVisible('foo');
    }
}
