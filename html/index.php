<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RouteHandler;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($container): void {
    $r->get('/', $container->get(IndexHandler::class));
    $r->post('/run', $container->get(CreateRunHandler::class));
    $r->get('/run/{hash:[a-zA-Z0-9]+}', $container->get(GetRunHandler::class));
});

/** @var Request $request */
$request = $container->get(Request::class);

(new RouteHandler())->handle(
    $dispatcher->dispatch($request->getMethod(), $request->getPathInfo())
)->send();
