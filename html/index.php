<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\HandleRoute;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use function FastRoute\simpleDispatcher;

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
    $r->get('/', function () use ($container): IndexHandler {
        return $container->get(IndexHandler::class);
    });

    $r->post('/run', function () use ($container): CreateRunHandler {
        return $container->get(CreateRunHandler::class);
    });

    $r->get('/run/{uuid:[a-f0-9-]+}', function () use ($container): GetRunHandler {
        return $container->get(GetRunHandler::class);
    });
});

/** @var Request $request */
$request = $container->get(Request::class);

$response = (new HandleRoute())(
    $dispatcher->dispatch($request->getMethod(), $request->getPathInfo())
);

$response->headers->add([
    'Content-Security-Policy' => "default-src 'self'; font-src data:;",
]);

$response->send();
