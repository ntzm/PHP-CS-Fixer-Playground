<?php

declare(strict_types=1);

use function FastRoute\cachedDispatcher;
use FastRoute\RouteCollector;
use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\HandleRoute;
use Symfony\Component\HttpFoundation\Request;

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
    'X-Frame-Options' => 'SAMEORIGIN',
    'X-XSS-Protection' => '1; mode=block',
    'X-Content-Type-Options' => 'nosniff',
    'Referrer-Policy' => 'no-referrer',
]);

$appUrl = getenv('APP_URL');

if (strpos($appUrl, 'https://') === 0) {
    $response->headers->add([
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    ]);
}

$response->send();
