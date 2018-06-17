<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\ServiceProvider;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\ParseRulesFromRequest;
use PhpCsFixerPlayground\ParseRulesFromRequestInterface;
use PhpCsFixerPlayground\ServiceProvider\ParseRulesFromRequestServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ServiceProvider\ParseRulesFromRequestServiceProvider
 */
final class ParseRulesFromRequestServiceProviderTest extends TestCase
{
    public function testProvides(): void
    {
        $provider = new ParseRulesFromRequestServiceProvider();

        $this->assertTrue($provider->provides(ParseRulesFromRequestInterface::class));
    }

    public function testRegisters(): void
    {
        $provider = new ParseRulesFromRequestServiceProvider();
        $provider->setContainer((new Container())->delegate(new ReflectionContainer()));
        $provider->register();

        $parseRulesFromRequest = $provider->getContainer()->get(ParseRulesFromRequestInterface::class);

        $this->assertInstanceOf(ParseRulesFromRequest::class, $parseRulesFromRequest);
    }
}
