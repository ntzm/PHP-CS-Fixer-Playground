<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\UrlGenerator;
use PhpCsFixerPlayground\UrlGeneratorInterface;

final class UrlGeneratorServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        UrlGeneratorInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(UrlGeneratorInterface::class, UrlGenerator::class)
            ->addArgument(getenv('APP_URL'))
        ;
    }
}
