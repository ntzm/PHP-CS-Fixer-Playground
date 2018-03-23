<?php

namespace PhpCsFixerPlayground\Handler;

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
        $run = $this->runs->getById($vars['id']);

        require __DIR__.'/../../templates/index.php';
    }
}
