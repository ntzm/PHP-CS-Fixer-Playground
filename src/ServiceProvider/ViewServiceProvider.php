<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactoryInterface;
use PhpCsFixerPlayground\View\TwigExtension;
use PhpCsFixerPlayground\View\ViewFactory;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;
use SebastianBergmann\Diff\Differ;
use Twig\Cache\FilesystemCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class ViewServiceProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        ViewFactoryInterface::class,
        Environment::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(Environment::class, function (): Environment {
                $loader = new FilesystemLoader(__DIR__.'/../../templates');
                $twig = new Environment($loader, [
                    'cache' => new FilesystemCache(__DIR__.'/../../data/cache/twig'),
                    'strict_variables' => true,
                ]);

                $twig->addExtension(new TwigExtension());

                return $twig;
            })
        ;

        $this->container
            ->add(ViewFactoryInterface::class, ViewFactory::class)
            ->withArgument(Environment::class)
            ->withArgument(Differ::class)
            ->withArgument(FixerCollectionFactoryInterface::class)
            ->withArgument(PhpCsFixerVersionFactoryInterface::class)
        ;
    }
}
