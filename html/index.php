<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\HandleRoute;
use Symfony\Component\HttpFoundation\Request;
use function FastRoute\cachedDispatcher;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();

$dispatcher = cachedDispatcher(function (RouteCollector $r): void {
    $r->get('/', IndexHandler::class);
    $r->post('/run', CreateRunHandler::class);
    $r->get('/run/{uuid:[a-f0-9-]+}', GetRunHandler::class);
}, ['cacheFile' => __DIR__.'/../data/cache/route.cache']);

/** @var Request $request */
$request = $container->get(Request::class);

$response = (new HandleRoute($container))->__invoke(
    $dispatcher->dispatch($request->getMethod(), $request->getPathInfo())
);

$response->headers->add([
    'Content-Security-Policy' => "default-src 'self'",
]);

$response->send();
