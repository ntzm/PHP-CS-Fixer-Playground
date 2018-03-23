<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\RunRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetRunHandler implements HandlerInterface
{
    private $runs;

    public function __construct(RunRepository $runs)
    {
        $this->runs = $runs;
    }

    public function __invoke(Request $request, array $vars): Response
    {
        $run = $this->runs->getById((int) $vars['id']);

        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $code = $run->getCode();
        $fixers = $run->getRules();
        $result = $run->getResult();

        return new Response(
            require __DIR__.'/../../templates/index.php'
        );
    }
}
