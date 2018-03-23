<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use PhpCsFixerPlayground\ConnectionResolver;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RunNotFoundException;
use PhpCsFixerPlayground\RunRepository;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$connectionResolver = new ConnectionResolver();

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($connectionResolver) {
    $r->get('/', function() {
        return new IndexHandler();
    });

    $r->post('/', function () use ($connectionResolver) {
        return new CreateRunHandler(new RunRepository($connectionResolver->resolve()));
    });

    $r->get('/{id:\d+}', function () use ($connectionResolver) {
        return new GetRunHandler(new RunRepository($connectionResolver->resolve()));
    });
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        die('404');
    case Dispatcher::METHOD_NOT_ALLOWED:
        die('not allowed');
    case Dispatcher::FOUND:
        /** @var HandlerInterface $handler */
        $handler = $routeInfo[1]();
        $vars = $routeInfo[2];

        try {
            $handler($request, $vars);
        } catch (RunNotFoundException $e) {
            die('404');
        }
}
