<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    /**
     * @throws \PhpCsFixerPlayground\NotFoundException
     */
    public function __invoke(array $vars): Response;
}
