<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactory;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactoryInterface;
use PhpCsFixerPlayground\ServiceProvider\PhpCsFixerVersionServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\PhpCsFixerVersionServiceProvider
 */
final class PhpCsFixerVersionServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new PhpCsFixerVersionServiceProvider();

        $this->assertTrue($provider->provides(PhpCsFixerVersionFactoryInterface::class));
    }

    public function testRegisters(): void
    {
        $provider = new PhpCsFixerVersionServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $phpCsFixerVersionFactory = $provider->getContainer()->get(PhpCsFixerVersionFactoryInterface::class);

        $this->assertInstanceOf(PhpCsFixerVersionFactory::class, $phpCsFixerVersionFactory);
    }
}
