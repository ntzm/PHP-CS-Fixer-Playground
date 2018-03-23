<?php

namespace PhpCsFixerPlayground\Handler;

use Symfony\Component\HttpFoundation\Request;

interface HandlerInterface
{
    public function __invoke(Request $request, array $vars);
}
