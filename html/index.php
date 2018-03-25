<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RunNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();

/** @var Request $request */
$request = $container->get(Request::class);

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($container): void {
    $r->get('/', function() use ($container): IndexHandler {
        return $container->get(IndexHandler::class);
    });

    $r->post('/', function () use ($container): CreateRunHandler {
        return $container->get(CreateRunHandler::class);
    });

    $r->get('/{hash:[a-zA-Z0-9]+}', function () use ($container): GetRunHandler {
        return $container->get(GetRunHandler::class);
    });
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        Response::create('Not Found', Response::HTTP_NOT_FOUND)->send();
    case Dispatcher::METHOD_NOT_ALLOWED:
        Response::create('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED)->send();
    case Dispatcher::FOUND:
        /** @var HandlerInterface $handler */
        $handler = $routeInfo[1]();
        $vars = $routeInfo[2];

        try {
            $handler($vars)->send();
        } catch (RunNotFoundException $e) {
            Response::create('Not Found', Response::HTTP_NOT_FOUND)->send();
        }
}
