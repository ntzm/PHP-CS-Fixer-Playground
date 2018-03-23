<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\FixerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class IndexHandler implements HandlerInterface
{
    public function __invoke(Request $request, array $vars): Response
    {
        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $code = "<?php\n\n";
        $fixers = [];

        return new Response(
            require __DIR__.'/../../templates/index.php'
        );
    }
}
