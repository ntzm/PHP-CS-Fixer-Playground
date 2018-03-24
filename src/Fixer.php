<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

final class Fixer
{
    public function fix(string $code, array $rules): string
    {
        $file = new MockSplFileInfo([]);

        $tokens = Tokens::fromCode($code);

        $fixers = FixerFactory::create()
            ->registerBuiltInFixers()
            ->useRuleSet(new RuleSet($rules))
            ->getFixers();

        foreach ($fixers as $fixer) {
            if (!$fixer->isCandidate($tokens)) {
                continue;
            }

            if (!$fixer->supports($file)) {
                continue;
            }

            if ($fixer instanceof ConfigurableFixerInterface) {
                try {
                    $fixer->configure([]);
                } catch (InvalidFixerConfigurationException $e) {
                    // TODO
                    continue;
                }
            }

            $fixer->fix($file, $tokens);

            if ($tokens->isChanged()) {
                $tokens->clearEmptyTokens();
                $tokens->clearChanged();
            }
        }

        return $tokens->generateCode();
    }
}
