<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcher;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcherInterface;

final class VersionSwitcherServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        VersionSwitcherInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            VersionSwitcherInterface::class,
            VersionSwitcher::class
        );
    }
}
