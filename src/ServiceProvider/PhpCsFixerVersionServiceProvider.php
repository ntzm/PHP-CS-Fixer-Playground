<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use GuzzleHttp\ClientInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSaver;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSaverInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcher;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcherInterface;

final class PhpCsFixerVersionServiceProvider extends AbstractServiceProvider
{
    private const VERSION_DIR = __DIR__.'/../../data/php-cs-fixer-versions';

    protected $provides = [
        VersionSaverInterface::class,
        VersionSwitcherInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(VersionSaverInterface::class, VersionSaver::class)
            ->withArgument(ClientInterface::class)
            ->withArgument(self::VERSION_DIR)
        ;

        $this->container
            ->add(VersionSwitcherInterface::class, VersionSwitcher::class)
            ->withArgument(self::VERSION_DIR)
        ;
    }
}
