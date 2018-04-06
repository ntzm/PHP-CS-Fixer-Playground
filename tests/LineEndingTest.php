<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\LineEnding;
use PHPUnit\Framework\TestCase;

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
}
