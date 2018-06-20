<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use PhpCsFixer\RuleSet;

interface FixerCollectionFactoryInterface
{
    public function all(): FixerCollection;

    public function fromRuleSet(RuleSet $ruleSet): FixerCollection;
}
