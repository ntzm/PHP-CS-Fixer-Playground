<?php

namespace PhpCsFixerPlayground\Handler;

use ParseError;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Fixer;
use PhpCsFixerPlayground\RunRepository;
use Symfony\Component\HttpFoundation\Request;

final class CreateRunHandler implements HandlerInterface
{
    private $runs;

    public function __construct(RunRepository $runs)
    {
        $this->runs = $runs;
    }

    public function __invoke(Request $request, array $vars)
    {
        $code = $request->query->get('code');

        $availableFixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

        $availableFixerNames = array_map(function (FixerInterface $fixer): string {
            return $fixer->getName();
        }, $availableFixers);

        if (isset($_GET['fixers']) && is_array($_GET['fixers'])) {
            $fixers = array_filter($_GET['fixers'], function ($fixerName) use ($availableFixerNames): bool {
                return in_array($fixerName, $availableFixerNames, true);
            });
        } else {
            $fixers = [];
        }

        try {
            $fixed = (new Fixer())->fix($code, $fixers);

            $result = highlight_string($fixed, true);
        } catch (ParseError $e) {
            $result = htmlentities($e->getMessage());
        }
    }
}
