<?php

namespace PhpCsFixerPlayground\Handler;

use ParseError;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Fixer;
use PhpCsFixerPlayground\Run;
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

        if (is_array($requestedFixers = $request->query->get('fixers'))) {
            $fixers = array_filter($requestedFixers, function ($fixerName) use ($availableFixerNames): bool {
                return in_array($fixerName, $availableFixerNames, true);
            });
        } else {
            $fixers = [];
        }

        try {
            $result = (new Fixer())->fix($code, $fixers);
        } catch (ParseError $e) {
            $result = $e->getMessage();
        }

        $run = new Run(
            uniqid('', true),
            $code,
            $result,
            $fixers
        );

        $this->runs->save($run);

        header(
            sprintf('Location: /%s', $run->getId())
        );
        die();
    }
}