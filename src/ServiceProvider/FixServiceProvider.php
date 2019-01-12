<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\Fix\Fix;
use PhpCsFixerPlayground\Fix\FixInterface;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;

final class FixServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        FixInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(FixInterface::class, Fix::class)
            ->addArgument(FixerCollectionFactoryInterface::class)
        ;
    }
}
