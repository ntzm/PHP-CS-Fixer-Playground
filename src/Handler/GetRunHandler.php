<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Fixer\FixerInterface;
use PhpCsFixerPlayground\Issue;
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
            $deprecationMessages = $report->getDeprecationMessages();
        } catch (Throwable $e) {
            $result = $e->getMessage();
            $appliedFixers = [];
            $deprecationMessages = [];
        }

        $configFile = new ConfigFile(
            $run->getRules(),
            $run->getIndent(),
            $run->getLineEnding()
        );

        $issue = new Issue(
            '',
            $run->getCode(),
            $result,
            $configFile,
            PHP_VERSION,
            '1.2'
        );

        return new Response(
            $this->viewFactory->make(
                $run,
                $result,
                $appliedFixers,
                $deprecationMessages,
                $configFile,
                $issue
            )
        );
    }
}
