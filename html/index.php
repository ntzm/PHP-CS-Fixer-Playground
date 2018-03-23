<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Hashids\Hashids;
use PhpCsFixerPlayground\ConnectionResolver;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RunNotFoundException;
use PhpCsFixerPlayground\RunRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$connection = (new ConnectionResolver())->resolve();
$hashids = new Hashids(getenv('HASHIDS_SECRET'), 10);

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($connection, $hashids) {
    $r->get('/', function() {
        return new IndexHandler();
    });

    $r->post('/', function () use ($connection, $hashids) {
        return new CreateRunHandler(new RunRepository($connection, $hashids));
    });

    $r->get('/{hash:[a-zA-Z0-9]+}', function () use ($connection, $hashids) {
        return new GetRunHandler(new RunRepository($connection, $hashids));
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
            $handler($request, $vars)->send();
        } catch (RunNotFoundException $e) {
            Response::create('Not Found', Response::HTTP_NOT_FOUND)->send();
        }
}
