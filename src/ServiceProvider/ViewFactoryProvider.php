<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\View\ViewFactory;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use SebastianBergmann\Diff\Differ;
use Twig\Environment;

final class ViewFactoryProvider extends AbstractServiceProvider
{
    protected $provides = [
        ViewFactoryInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(ViewFactoryInterface::class, ViewFactory::class)
            ->withArgument(Environment::class)
            ->withArgument(Differ::class)
            ->withArgument(FixerFactory::class)
        ;
    }
}
