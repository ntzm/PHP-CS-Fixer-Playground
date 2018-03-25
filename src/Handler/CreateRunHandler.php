<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Run;
use PhpCsFixerPlayground\RunRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateRunHandler implements HandlerInterface
{
    private $runs;

    private $request;

    public function __construct(RunRepositoryInterface $runs, Request $request)
    {
        $this->runs = $runs;
        $this->request = $request;
    }

    public function __invoke(array $vars): Response
    {
        $code = $this->request->request->get('code');

        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $availableFixerNames = array_map(function (FixerInterface $fixer): string {
            return $fixer->getName();
        }, $availableFixers);

        if (is_array($requestedFixers = $this->request->request->get('fixers'))) {
            $fixers = array_filter($requestedFixers, function ($fixerName) use ($availableFixerNames): bool {
                return in_array($fixerName, $availableFixerNames, true);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            $fixers = [];
        }

        foreach ($fixers as &$value) {
            if ($value === 'true') {
                $value = true;
            }

            if ($value === 'false') {
                $value = false;
            }
        }

        $run = new Run($code, $fixers);

        $run = $this->runs->save($run);

        return new RedirectResponse(
            sprintf('/%s', $run->getHash())
        );
    }
}
