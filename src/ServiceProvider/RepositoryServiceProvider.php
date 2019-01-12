<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use Doctrine\ORM\EntityManagerInterface;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\Run\RunRepository;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;

final class RepositoryServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        RunRepositoryInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(RunRepositoryInterface::class, RunRepository::class)
            ->addArgument(EntityManagerInterface::class)
        ;
    }
}
