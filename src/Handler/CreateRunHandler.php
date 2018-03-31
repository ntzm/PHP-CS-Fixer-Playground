<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixerPlayground\RequestRuleParserInterface;
use PhpCsFixerPlayground\Run\Run;
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
        $code = $this->request->request->get('code');

        $rules = $this->requestRuleParser->parse(
            $this->request->request->get('fixers')
        );

        $indent = $this->request->request->get('indent');
        $lineEnding = $this->request->request->get('line_ending');

        $run = new Run($code, $rules, $indent, $lineEnding);

        $run = $this->runs->save($run);

        return new RedirectResponse(
            sprintf('/%s', $run->getHash())
        );
    }
}
