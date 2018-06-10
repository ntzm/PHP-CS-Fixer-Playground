<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\UrlGenerator;
use PhpCsFixerPlayground\UrlGeneratorInterface;

final class UrlGeneratorServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        UrlGeneratorInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(UrlGeneratorInterface::class, UrlGenerator::class)
            ->withArgument(getenv('APP_URL'))
        ;
    }
}
