<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ConfigFileGeneratorInterface;
use PhpCsFixerPlayground\Fixer\FixerInterface;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
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
        try {
            $uuid = Uuid::fromString($vars['uuid']);
        } catch (InvalidUuidStringException $e) {
            throw new RunNotFoundException();
        }

        $run = $this->runs->findByUuid($uuid);

        try {
            $report = $this->fixer->fix(
                $run->getCode(),
                $run->getRules(),
                $run->getIndent(),
                $run->getLineEnding()
            );

            $result = $report->getResult();
            $appliedFixers = $report->getAppliedFixers();
        } catch (Throwable $e) {
            $result = $e->getMessage();
            $appliedFixers = [];
        }

        return new Response(
            $this->viewFactory->make(
                $run,
                $result,
                $appliedFixers,
                $this->configFileGenerator->generate(
                    $run->getRules(),
                    $run->getIndent(),
                    $run->getLineEnding()
                )
            )
        );
    }
}
