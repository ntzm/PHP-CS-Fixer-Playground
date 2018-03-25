<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Hashids\Hashids;
use Hashids\HashidsInterface;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\ConnectionResolver;
use PhpCsFixerPlayground\Handler\CreateRunHandler;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\RunNotFoundException;
use PhpCsFixerPlayground\RunRepository;
use PhpCsFixerPlayground\RunRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();

$container = new Container();
$container->delegate(new ReflectionContainer());

$container->add(
    HashidsInterface::class,
    new Hashids(getenv('HASHIDS_SECRET'), 10)
);

$container->add(
    PDO::class,
    (new ConnectionResolver())->resolve()
);

$container
    ->add(RunRepositoryInterface::class, RunRepository::class)
    ->withArgument(PDO::class)
    ->withArgument(HashidsInterface::class)
;

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
            $handler($request, $vars)->send();
        } catch (RunNotFoundException $e) {
            Response::create('Not Found', Response::HTTP_NOT_FOUND)->send();
        }
}
