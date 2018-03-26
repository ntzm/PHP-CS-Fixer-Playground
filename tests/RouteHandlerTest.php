<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use FastRoute\Dispatcher;
use Mockery;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\RouteHandler;
use PhpCsFixerPlayground\RunNotFoundException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \PhpCsFixerPlayground\RouteHandler
 */
final class RouteHandlerTest extends TestCase
{
	public function testHandlesNotFound(): void
	{
		$routeHandler  = new RouteHandler();

		$response = $routeHandler->handle([Dispatcher::NOT_FOUND]);

		$this->assertSame('Not Found', $response->getContent());
		$this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
	}

	public function testHandlesMethodNotAllowed(): void
	{
		$routeHandler = new RouteHandler();

		$response = $routeHandler->handle([Dispatcher::METHOD_NOT_ALLOWED, ['GET', 'PUT']]);

		$this->assertSame('Method Not Allowed', $response->getContent());
		$this->assertSame(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
	}

	public function testHandle(): void
	{
		$routeHandler = new RouteHandler();

		$response = new Response('Foo Bar');

		$handler = Mockery::mock(HandlerInterface::class);
		$handler
			->expects('__invoke')
			->withArgs([['foo' => 'bar']])
			->andReturn($response)
		;

		$actualResponse = $routeHandler->handle([Dispatcher::FOUND, $handler, ['foo' => 'bar']]);
		$this->assertSame($response, $actualResponse);
	}

	public function testHandleThrowsRunNotFound(): void
	{
		$routeHandler = new RouteHandler();

		$handler = Mockery::mock(HandlerInterface::class);
		$handler
			->expects('__invoke')
			->andThrow(RunNotFoundException::fromHash('foo'))
		;

		$response = $routeHandler->handle([Dispatcher::FOUND, $handler, []]);

		$this->assertSame('Not Found', $response->getContent());
		$this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
	}
}
