<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use org\bovigo\vfs\vfsStream;
use PhpCsFixer\Config;
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

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAutoloadsFromDirectory(): void
    {
        $path = vfsStream::setup('root', null, [
            '2.11.1' => [
                'Config.php' => '<?php namespace PhpCsFixer; class Config { public function getFormat() { return "bar"; } }',
            ],
        ]);

        $switcher = new VersionSwitcher($path->url());

        $switcher->switchTo(new PhpCsFixerVersion('2.11.1'));

        $this->assertSame('bar', (new Config())->getFormat());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAutoloadsFromDirectoryFailsWhenDoesNotExist(): void
    {
        $path = vfsStream::setup('root', null, [
            '2.11.1' => [
                'Config.php' => '<?php namespace PhpCsFixer; class Config { public function getFormat() { return "bar"; } }',
            ],
        ]);

        $switcher = new VersionSwitcher($path->url());

        $switcher->switchTo(new PhpCsFixerVersion('2.11.1'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot find class PhpCsFixer\Finder on PHP-CS-Fixer version 2.11.1, tried file vfs://root/2.11.1/Finder.php');

        new Finder();
    }
}
