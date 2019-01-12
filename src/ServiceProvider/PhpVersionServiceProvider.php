<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpVersion\PhpVersionFactory;
use PhpCsFixerPlayground\PhpVersion\PhpVersionFactoryInterface;

final class PhpVersionServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        PhpVersionFactoryInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            PhpVersionFactoryInterface::class,
            PhpVersionFactory::class
        );
    }
}
