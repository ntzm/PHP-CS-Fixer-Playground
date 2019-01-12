<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;

final class FixerCollectionFactory implements FixerCollectionFactoryInterface
{
    public function all(): FixerCollection
    {
        return $this->wrap(
            (new FixerFactory())
                ->registerBuiltInFixers()
                ->getFixers(),
        );
    }

    public function fromRuleSet(RuleSet $ruleSet): FixerCollection
    {
        return $this->wrap(
            (new FixerFactory())
                ->registerBuiltInFixers()
                ->useRuleSet($ruleSet)
                ->getFixers(),
        );
    }

    private function wrap(array $fixers): FixerCollection
    {
        return new FixerCollection(
            array_map(function (FixerInterface $fixer): FixerWrapper {
                return new FixerWrapper($fixer);
            }, $fixers)
        );
    }
}
