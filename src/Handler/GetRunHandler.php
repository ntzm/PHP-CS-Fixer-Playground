<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\Fix\FixInterface;
use PhpCsFixerPlayground\Issue;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionFactoryInterface;
use PhpCsFixerPlayground\PhpVersion\PhpVersionFactoryInterface;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use PhpCsFixerPlayground\UrlGeneratorInterface;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class GetRunHandler implements HandlerInterface
{
    /** @var RunRepositoryInterface */
    private $runs;

    /** @var ViewFactoryInterface */
    private $viewFactory;

    /** @var FixInterface */
    private $fix;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var PhpVersionFactoryInterface */
    private $phpVersionFactory;

    /** @var PhpCsFixerVersionFactoryInterface */
    private $phpCsFixerVersionFactory;

    public function __construct(
        RunRepositoryInterface $runs,
        ViewFactoryInterface $viewFactory,
        FixInterface $fix,
        UrlGeneratorInterface $urlGenerator,
        PhpVersionFactoryInterface $phpVersionFactory,
        PhpCsFixerVersionFactoryInterface $phpCsFixerVersionFactory
    ) {
        $this->runs = $runs;
        $this->viewFactory = $viewFactory;
        $this->fix = $fix;
        $this->urlGenerator = $urlGenerator;
        $this->phpVersionFactory = $phpVersionFactory;
        $this->phpCsFixerVersionFactory = $phpCsFixerVersionFactory;
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
            $report = $this->fix->__invoke(
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

        $configFile = $run->getConfigFile();

        $issue = new Issue(
            $this->urlGenerator->generateUrlForRun($run),
            $run->getCode(),
            $result,
            $configFile,
            $this->phpVersionFactory->make(),
            $this->phpCsFixerVersionFactory->make()
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
