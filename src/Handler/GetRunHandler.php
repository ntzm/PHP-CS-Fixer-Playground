<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use ParseError;
use PhpCsFixerPlayground\Fixer;
use PhpCsFixerPlayground\RunRepositoryInterface;
use PhpCsFixerPlayground\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class GetRunHandler implements HandlerInterface
{
    private $runs;

    private $viewFactory;

    public function __construct(RunRepositoryInterface $runs, ViewFactoryInterface $viewFactory)
    {
        $this->runs = $runs;
        $this->viewFactory = $viewFactory;
    }

    public function __invoke(array $vars): Response
    {
        $run = $this->runs->getByHash($vars['hash']);

        try {
            $result = (new Fixer())->fix($run->getCode(), $run->getRules());
        } catch (ParseError $e) {
            $result = $e->getMessage();
        }

        return new Response(
            $this->viewFactory->make($run->getCode(), $run->getRules(), $result)
        );
    }
}
