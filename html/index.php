<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Handler\Api\GetFixersHandler;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RouteHandler;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();
$container->delegate(new ReflectionContainer());

/** @var SplFileInfo $file */
foreach ((new Finder())->in(__DIR__.'/../src/ServiceProvider') as $file) {
    $class = sprintf(
        'PhpCsFixerPlayground\\ServiceProvider\\%s',
        $file->getBasename('.php')
    );

    $container->addServiceProvider($class);
}

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($container): void {
    $r->get('/', $container->get(IndexHandler::class));
    $r->post('/run', $container->get(CreateRunHandler::class));
    $r->get('/run/{hash:[a-zA-Z0-9]+}', $container->get(GetRunHandler::class));
    $r->get('/api/fixers/{version:[0-9.]+}', $container->get(GetFixersHandler::class));
});

/** @var Request $request */
$request = $container->get(Request::class);

(new RouteHandler())->handle(
    $dispatcher->dispatch($request->getMethod(), $request->getPathInfo())
)->send();
