<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use Doctrine\ORM\EntityManagerInterface;
use Hashids\HashidsInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepository;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepositoryInterface;
use PhpCsFixerPlayground\Run\RunRepository;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;

final class RepositoryServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        RunRepositoryInterface::class,
        PhpCsFixerVersionRepositoryInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(RunRepositoryInterface::class, RunRepository::class)
            ->withArgument(EntityManagerInterface::class)
            ->withArgument(HashidsInterface::class)
        ;

        $this->container
            ->add(
                PhpCsFixerVersionRepositoryInterface::class,
                PhpCsFixerVersionRepository::class
            )
            ->withArgument(EntityManagerInterface::class)
        ;
    }
}
