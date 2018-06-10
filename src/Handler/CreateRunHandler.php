<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\ParseRulesFromRequestInterface;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateRunHandler implements HandlerInterface
{
    /**
     * @var RunRepositoryInterface
     */
    private $runs;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ParseRulesFromRequestInterface
     */
    private $parseRulesFromRequest;

    public function __construct(
        RunRepositoryInterface $runs,
        Request $request,
        ParseRulesFromRequestInterface $requestRuleParser
    ) {
        $this->runs = $runs;
        $this->request = $request;
        $this->parseRulesFromRequest = $requestRuleParser;
    }

    public function __invoke(array $vars): Response
    {
        $query = $this->request->request;

        $run = new Run(
            $query->get('code'),
            $this->parseRulesFromRequest->__invoke($query->get('fixers')),
            $query->get('indent'),
            LineEnding::fromVisible($query->get('line_ending'))
        );

        $this->runs->save($run);

        return new RedirectResponse("/run/{$run->getId()->toString()}");
    }
}
