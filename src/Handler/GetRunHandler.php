<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ConfigFileGeneratorInterface;
use PhpCsFixerPlayground\Fixer\FixerInterface;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class GetRunHandler implements HandlerInterface
{
    /**
     * @var RunRepositoryInterface
     */
    private $runs;

    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var FixerInterface
     */
    private $fixer;

    /**
     * @var ConfigFileGeneratorInterface
     */
    private $configFileGenerator;

    public function __construct(
        RunRepositoryInterface $runs,
        ViewFactoryInterface $viewFactory,
        FixerInterface $fixer,
        ConfigFileGeneratorInterface $configFileGenerator
    ) {
        $this->runs = $runs;
        $this->viewFactory = $viewFactory;
        $this->fixer = $fixer;
        $this->configFileGenerator = $configFileGenerator;
    }

    public function __invoke(array $vars): Response
    {
        $run = $this->runs->getByHash($vars['hash']);

        try {
            $report = $this->fixer->fix(
                $run->getCode(),
                $run->getRules(),
                $run->getIndent(),
                $run->getRealLineEnding()
            );

            $result = $report->getResult();
            $appliedFixers = $report->getAppliedFixers();
        } catch (Throwable $e) {
            $result = $e->getMessage();
            $appliedFixers = [];
        }

        return new Response(
            $this->viewFactory->make(
                $run->getCode(),
                $run->getRules(),
                $result,
                $appliedFixers,
                $run->getIndent(),
                $run->getLineEnding(),
                $this->configFileGenerator->generate(
                    $run->getRules(),
                    $run->getIndent(),
                    $run->getLineEnding()
                )
            )
        );
    }
}
