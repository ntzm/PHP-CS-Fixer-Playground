<?php

namespace PhpCsFixerPlayground\Handler;

use function PhpCsFixerPlayground\view;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    public function __invoke(Request $request, array $vars): Response
    {
        return new Response(view("<?php\n\n", [], ''));
    }
}
