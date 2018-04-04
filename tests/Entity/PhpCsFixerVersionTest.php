<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Entity;

use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Entity\PhpCsFixerVersion
 */
final class PhpCsFixerVersionTest extends TestCase
{
    public function testGetVersion(): void
    {
        $version = new PhpCsFixerVersion('2.11.1');
        $this->assertSame('2.11.1', $version->getVersion());
    }
}
