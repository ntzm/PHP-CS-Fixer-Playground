<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Fix\Fix;
use PhpCsFixerPlayground\Fix\FixInterface;
use PhpCsFixerPlayground\ServiceProvider\FixServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\FixServiceProvider
 */
final class FixServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new FixServiceProvider();

        $this->assertTrue($provider->provides(FixInterface::class));
    }

    public function testRegisters(): void
    {
        $provider = new FixServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $fix = $provider->getContainer()->get(FixInterface::class);

        $this->assertInstanceOf(Fix::class, $fix);
    }
}
