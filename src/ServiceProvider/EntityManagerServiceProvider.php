<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use Doctrine\ORM\EntityManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\EntityManagerResolver;

final class EntityManagerServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        EntityManagerInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            EntityManagerInterface::class,
            new EntityManagerResolver()
        );
    }
}