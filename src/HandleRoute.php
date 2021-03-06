<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use FastRoute\Dispatcher;
use Symfony\Component\HttpFoundation\Response;

final class HandleRoute
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke(array $routeInfo): Response
    {
        $status = $routeInfo[0];

        if ($status === Dispatcher::NOT_FOUND) {
            return $this->handleNotFound();
        }

        if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->handleMethodNotAllowed();
        }

        /** @var Handler\HandlerInterface $handler */
        $handler = $this->container->get($routeInfo[1]);

        /** @var array $vars */
        $vars = $routeInfo[2];

        try {
            return $handler($vars);
        } catch (NotFoundException $e) {
            return $this->handleNotFound();
        }
    }

    private function handleNotFound(): Response
    {
        return new Response(
            'Not Found',
            Response::HTTP_NOT_FOUND,
        );
    }

    private function handleMethodNotAllowed(): Response
    {
        return new Response(
            'Method Not Allowed',
            Response::HTTP_METHOD_NOT_ALLOWED,
        );
    }
}
