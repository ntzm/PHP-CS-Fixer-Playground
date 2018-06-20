<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactory;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;

final class FixerCollectionFactoryServiceProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        FixerCollectionFactoryInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            FixerCollectionFactoryInterface::class,
            FixerCollectionFactory::class
        );
    }
}
