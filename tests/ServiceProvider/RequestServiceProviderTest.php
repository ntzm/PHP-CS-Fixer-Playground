<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\ServiceProvider\RequestServiceProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\RequestServiceProvider
 */
final class RequestServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new RequestServiceProvider();

        $this->assertTrue($provider->provides(Request::class));
    }

    /** @runInSeparateProcess */
    public function testRegisters(): void
    {
        $provider = new RequestServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $_GET['foo'] = 'bar';

        /** @var Request $request */
        $request = $provider->getContainer()->get(Request::class);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('bar', $request->query->get('foo'));
    }
}
