<?php

namespace PhpCsFixerPlayground\Handler;

use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    public function __invoke(array $vars): Response;
}
