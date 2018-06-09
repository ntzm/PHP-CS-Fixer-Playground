<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Run;

use PhpCsFixerPlayground\Run\RunNotFoundException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * @covers \PhpCsFixerPlayground\Run\RunNotFoundException
 */
final class RunNotFoundExceptionTest extends TestCase
{
    public function testFromHash(): void
    {
        $uuid = $this->createMock(UuidInterface::class);
        $uuid
            ->expects($this->once())
            ->method('toString')
            ->willReturn('foo')
        ;

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with UUID foo');

        throw RunNotFoundException::fromUuid($uuid);
    }
}
