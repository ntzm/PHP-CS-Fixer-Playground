<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\RunRepository;
use Symfony\Component\HttpFoundation\Request;

final class GetRunHandler implements HandlerInterface
{
    private $runs;

    public function __construct(RunRepository $runs)
    {
        $this->runs = $runs;
    }

    public function __invoke(Request $request, array $vars)
    {
        $run = $this->runs->getById((int) $vars['id']);

        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $code = $run->getCode();
        $fixers = $run->getRules();
        $result = $run->getResult();

        require __DIR__.'/../../templates/index.php';
    }
}
