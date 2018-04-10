<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use PhpCsFixer\Finder;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcher;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class VersionSwitcherTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAlreadyAutoloaded(): void
    {
        class_exists(Finder::class);

        $switcher = new VersionSwitcher();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class PhpCsFixer\Finder already autoloaded');

        $switcher->switchTo(new PhpCsFixerVersion('2.11.1'));
    }
}
