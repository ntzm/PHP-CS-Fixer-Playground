<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\ConfigFileGenerator;
use PhpCsFixerPlayground\ConfigFileGeneratorInterface;

final class ConfigFileGeneratorServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ConfigFileGeneratorInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            ConfigFileGeneratorInterface::class,
            ConfigFileGenerator::class
        );
    }
}
