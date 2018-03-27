<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\FixerInterface;
use PhpCsFixerPlayground\RunRepositoryInterface;
use PhpCsFixerPlayground\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class GetRunHandler implements HandlerInterface
{
    private $runs;

    private $viewFactory;

    private $fixer;

    public function __construct(
        RunRepositoryInterface $runs,
        ViewFactoryInterface $viewFactory,
        FixerInterface $fixer
    ) {
        $this->runs = $runs;
        $this->viewFactory = $viewFactory;
        $this->fixer = $fixer;
    }

    public function __invoke(array $vars): Response
    {
        $run = $this->runs->getByHash($vars['hash']);

        try {
            $result = $this->fixer->fix(
                $run->getCode(),
                $run->getRules(),
                $run->getIndent(),
                $run->getRealLineEnding()
            );
        } catch (Throwable $e) {
            $result = $e->getMessage();
        }

        return new Response(
            $this->viewFactory->make(
                $run->getCode(),
                $run->getRules(),
                $result,
                $run->getIndent(),
                $run->getLineEnding()
            )
        );
    }
}
