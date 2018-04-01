<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use FastRoute\Dispatcher;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class RouteHandler
{
    public function handle(array $routeInfo): Response
    {
        $status = $routeInfo[0];

        if ($status === Dispatcher::NOT_FOUND) {
            return $this->handleNotFound();
        }

        if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->handleMethodNotAllowed();
        }

        /** @var Handler\HandlerInterface $handler */
        /** @var array $vars */
        [, $handler, $vars] = $routeInfo;

        try {
            return $handler($vars);
        } catch (RunNotFoundException $e) {
            return $this->handleNotFound();
        }
    }

    private function handleNotFound(): Response
    {
        return new Response(
            'Not Found',
            Response::HTTP_NOT_FOUND
        );
    }

    private function handleMethodNotAllowed(): Response
    {
        return new Response(
            'Method Not Allowed',
            Response::HTTP_METHOD_NOT_ALLOWED
        );
    }
}
