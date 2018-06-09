<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\RequestRuleParserInterface;
use PhpCsFixerPlayground\Entity\Run;
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
     * @var RequestRuleParserInterface
     */
    private $requestRuleParser;

    public function __construct(
        RunRepositoryInterface $runs,
        Request $request,
        RequestRuleParserInterface $requestRuleParser
    ) {
        $this->runs = $runs;
        $this->request = $request;
        $this->requestRuleParser = $requestRuleParser;
    }

    public function __invoke(array $vars): Response
    {
        $query = $this->request->request;

        $run = new Run(
            $query->get('code'),
            $this->requestRuleParser->parse($query->get('fixers')),
            $query->get('indent'),
            LineEnding::fromVisible($query->get('line_ending'))
        );

        $this->runs->save($run);

        return new RedirectResponse(
            sprintf('/run/%s', $run->getId()->toString())
        );
    }
}
