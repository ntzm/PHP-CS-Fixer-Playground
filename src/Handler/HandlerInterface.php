<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    /** @throws NotFoundException */
    public function __invoke(array $vars): Response;
}
