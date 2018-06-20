<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use PhpCsFixerPlayground\ServiceProvider\ViewServiceProvider;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

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
}
