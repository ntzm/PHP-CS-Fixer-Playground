<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fixer;

use InvalidArgumentException;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

final class Fixer implements FixerInterface
{
    /**
     * @var FixerFactory
     */
    private $fixerFactory;

    public function __construct(FixerFactory $fixerFactory)
    {
        $this->fixerFactory = $fixerFactory;
    }

    public function fix(
        string $code,
        array $rules,
        string $indent,
        string $lineEnding
    ): FixReport {
        $file = new MockSplFileInfo([]);

        $tokens = Tokens::fromCode($code);

        $fixers = $this->getFixers($rules, $indent, $lineEnding);

        $appliedFixers = [];

        foreach ($fixers as $fixer) {
            if (!$fixer->isCandidate($tokens) || !$fixer->supports($file)) {
                continue;
            }

            if ($fixer instanceof ConfigurableFixerInterface) {
                try {
                    $fixer->configure([]);
                } catch (InvalidArgumentException $e) {
                    // TODO
                    continue;
                }
            }

            $fixer->fix($file, $tokens);

            if ($tokens->isChanged()) {
                $tokens->clearEmptyTokens();
                $tokens->clearChanged();

                $appliedFixers[] = $fixer;
            }
        }

        return new FixReport($tokens->generateCode(), $appliedFixers);
    }

    /**
     * @param array<string, mixed> $rules
     * @param string               $indent
     * @param string               $lineEnding
     *
     * @return \PhpCsFixer\Fixer\FixerInterface[]
     */
    private function getFixers(
        array $rules,
        string $indent,
        string $lineEnding
    ): array {
        return $this->fixerFactory
            ->registerBuiltInFixers()
            ->setWhitespacesConfig(
                new WhitespacesFixerConfig($indent, $lineEnding)
            )
            ->useRuleSet(new RuleSet($rules))
            ->getFixers()
        ;
    }
}
