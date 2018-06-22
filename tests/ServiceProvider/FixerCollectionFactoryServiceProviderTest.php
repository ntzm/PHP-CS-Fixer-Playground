<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\ServiceProvider\FixerCollectionFactoryServiceProvider;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactory;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\FixerCollectionFactoryServiceProvider
 */
final class FixerCollectionFactoryServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new FixerCollectionFactoryServiceProvider();

        $this->assertTrue($provider->provides(FixerCollectionFactoryInterface::class));
    }

    public function testRegisters(): void
    {
        $provider = new FixerCollectionFactoryServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $fixerCollectionFactory = $provider->getContainer()->get(FixerCollectionFactoryInterface::class);

        $this->assertInstanceOf(FixerCollectionFactory::class, $fixerCollectionFactory);
    }
}
