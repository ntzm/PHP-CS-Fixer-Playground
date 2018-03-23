<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\FixerFactory;
use Symfony\Component\HttpFoundation\Request;

final class IndexHandler implements HandlerInterface
{
    public function __invoke(Request $request, array $vars)
    {
        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $code = "<?php\n\n";
        $fixers = [];

        require __DIR__.'/../../templates/index.php';
    }
}
