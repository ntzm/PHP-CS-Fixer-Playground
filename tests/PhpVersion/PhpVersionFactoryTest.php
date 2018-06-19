<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpVersion;

use PhpCsFixerPlayground\PhpVersion\PhpVersionFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpVersion\PhpVersionFactory
 */
final class PhpVersionFactoryTest extends TestCase
{
    public function test(): void
    {
        $factory = new PhpVersionFactory();

        $this->assertSame(PHP_VERSION, (string) $factory->make());
    }
}
