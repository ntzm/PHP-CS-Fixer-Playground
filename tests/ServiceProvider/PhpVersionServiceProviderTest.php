<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\PhpVersion\PhpVersionFactory;
use PhpCsFixerPlayground\PhpVersion\PhpVersionFactoryInterface;
use PhpCsFixerPlayground\ServiceProvider\PhpVersionServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\PhpVersionServiceProvider
 */
final class PhpVersionServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new PhpVersionServiceProvider();

        $this->assertTrue($provider->provides(PhpVersionFactoryInterface::class));
    }

    public function testRegisters(): void
    {
        $provider = new PhpVersionServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $fix = $provider->getContainer()->get(PhpVersionFactoryInterface::class);

        $this->assertInstanceOf(PhpVersionFactory::class, $fix);
    }
}
