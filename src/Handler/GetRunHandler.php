<?php

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\RunRepository;
use function PhpCsFixerPlayground\view;
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

        return new Response(
            view($run->getCode(), $run->getRules(), $run->getResult())
        );
    }
}
