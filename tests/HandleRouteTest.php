<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use FastRoute\Dispatcher;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\HandleRoute;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \PhpCsFixerPlayground\HandleRoute
 */
final class HandleRouteTest extends TestCase
{
    public function testHandlesNotFound(): void
    {
        $routeHandler = new HandleRoute();

        $response = $routeHandler([Dispatcher::NOT_FOUND]);

        $this->assertSame('Not Found', $response->getContent());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testHandlesMethodNotAllowed(): void
    {
        $routeHandler = new HandleRoute();

        $response = $routeHandler([Dispatcher::METHOD_NOT_ALLOWED, ['GET', 'PUT']]);

        $this->assertSame('Method Not Allowed', $response->getContent());
        $this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testHandle(): void
    {
        $routeHandler = new HandleRoute();

        $response = new Response('Foo Bar');

        /** @var HandlerInterface|MockObject $handler */
        $handler = $this->createMock(HandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('__invoke')
            ->with(['foo' => 'bar'])
            ->willReturn($response)
        ;

        $actualResponse = $routeHandler([Dispatcher::FOUND, function () use ($handler) { return $handler; }, ['foo' => 'bar']]);
        $this->assertSame($response, $actualResponse);
    }

    public function testHandleThrowsRunNotFound(): void
    {
        $routeHandler = new HandleRoute();

        /** @var HandlerInterface|MockObject $handler */
        $handler = $this->createMock(HandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('__invoke')
            ->with([])
            ->willThrowException(new RunNotFoundException())
        ;

        $response = $routeHandler([Dispatcher::FOUND, function () use ($handler) { return $handler; }, []]);

        $this->assertSame('Not Found', $response->getContent());
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
