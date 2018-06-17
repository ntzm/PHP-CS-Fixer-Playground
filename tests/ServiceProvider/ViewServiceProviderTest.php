<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\ServiceProvider\ViewServiceProvider;
use PhpCsFixerPlayground\View\TwigExtension;
use PhpCsFixerPlayground\View\ViewFactory;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PHPUnit\Framework\TestCase;
use Twig\Cache\FilesystemCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\ViewServiceProvider
 */
final class ViewServiceProviderTest extends TestCase
{
    public function testProvidesViewFactory(): void
    {
        $provider = new ViewServiceProvider();

        $this->assertTrue($provider->provides(ViewFactoryInterface::class));
    }

    public function testProvidesEnvironment(): void
    {
        $provider = new ViewServiceProvider();

        $this->assertTrue($provider->provides(Environment::class));
    }

    public function testRegistersViewFactory(): void
    {
        $provider = new ViewServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $viewFactory = $provider->getContainer()->get(ViewFactoryInterface::class);

        $this->assertInstanceOf(ViewFactory::class, $viewFactory);
    }

    public function testRegistersEnvironment(): void
    {
        $provider = new ViewServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        /** @var Environment $environment */
        $environment = $provider->getContainer()->get(Environment::class);

        $this->assertInstanceOf(FilesystemLoader::class, $environment->getLoader());
        $this->assertInstanceOf(FilesystemCache::class, $environment->getCache());
        $this->assertArrayHasKey(TwigExtension::class, $environment->getExtensions());
    }
}
