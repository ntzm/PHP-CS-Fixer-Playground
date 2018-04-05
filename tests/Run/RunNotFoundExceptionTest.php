<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Run;

use PhpCsFixerPlayground\Run\RunNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Run\RunNotFoundException
 */
final class RunNotFoundExceptionTest extends TestCase
{
    public function testFromHash(): void
    {
        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with hash foo');

        throw RunNotFoundException::fromHash('foo');
    }
}