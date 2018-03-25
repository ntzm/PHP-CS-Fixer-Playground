<?php

namespace PhpCsFixerPlayground\Handler;

use function PhpCsFixerPlayground\view;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    public function __invoke(array $vars): Response
    {
        return new Response(view("<?php\n\n", [], "<?php\n\n"));
    }
}
