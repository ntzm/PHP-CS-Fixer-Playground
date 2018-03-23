<?php

namespace PhpCsFixerPlayground\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    public function __invoke(Request $request, array $vars): Response;
}
