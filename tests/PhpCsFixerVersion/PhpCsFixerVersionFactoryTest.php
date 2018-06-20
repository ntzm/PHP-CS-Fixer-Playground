<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use PhpCsFixer\Console\Application;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpVersion\PhpCsFixerVersionFactory
 */
final class PhpCsFixerVersionFactoryTest extends TestCase
{
    public function test(): void
    {
        $factory = new PhpCsFixerVersionFactory();

        $this->assertSame(Application::VERSION.' '.Application::VERSION_CODENAME, (string) $factory->make());
    }
}
