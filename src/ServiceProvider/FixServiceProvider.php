<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Fix\Fix;
use PhpCsFixerPlayground\Fix\FixInterface;

final class FixServiceProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        FixInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(FixInterface::class, Fix::class)
            ->withArgument(FixerFactory::class)
        ;
    }
}
