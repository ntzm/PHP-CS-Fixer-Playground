<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Fixer\Fixer;
use PhpCsFixerPlayground\Fixer\FixerInterface;

final class FixerServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        FixerInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(FixerInterface::class, Fixer::class)
            ->withArgument(FixerFactory::class)
        ;
    }
}
