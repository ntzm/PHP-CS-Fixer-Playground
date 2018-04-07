<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\View\TwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Environment::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(Environment::class, function (): Environment {
                $loader = new FilesystemLoader(__DIR__.'/../../templates');
                $twig = new Environment($loader, ['strict_variables' => true]);

                $twig->addExtension(new TwigExtension());

                return $twig;
            });
    }
}
