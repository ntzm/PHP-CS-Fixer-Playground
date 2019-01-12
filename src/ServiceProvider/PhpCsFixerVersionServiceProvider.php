<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactory;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactoryInterface;

final class PhpCsFixerVersionServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        PhpCsFixerVersionFactoryInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            PhpCsFixerVersionFactoryInterface::class,
            PhpCsFixerVersionFactory::class
        );
    }
}
