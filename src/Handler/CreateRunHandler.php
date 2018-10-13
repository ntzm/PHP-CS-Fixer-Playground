<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\ParseRulesFromRequestInterface;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateRunHandler implements HandlerInterface
{
    /** @var RunRepositoryInterface */
    private $runs;

    /** @var Request */
    private $request;

    /** @var ParseRulesFromRequestInterface */
    private $parseRulesFromRequest;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        RunRepositoryInterface $runs,
        Request $request,
        ParseRulesFromRequestInterface $parseRulesFromRequest,
        LoggerInterface $logger
    ) {
        $this->runs = $runs;
        $this->request = $request;
        $this->parseRulesFromRequest = $parseRulesFromRequest;
        $this->logger = $logger;
    }

    public function __invoke(array $vars): Response
    {
        $query = $this->request->request;

        $this->logger->info('Creating run', iterator_to_array($query));

        $run = new Run(
            $query->get('code'),
            $this->parseRulesFromRequest->__invoke($query->get('fixers')),
            new Indent($query->get('indent')),
            LineEnding::fromVisible($query->get('line_ending'))
        );

        $this->runs->save($run);

        $this->logger->info('Created run', ['id' => $run->getId()->toString()]);

        return new RedirectResponse("/run/{$run->getId()->toString()}");
    }
}
