<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fixer;

use InvalidArgumentException;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixerPlayground\LineEnding;
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
        LineEnding $lineEnding
    ): FixReport {
        $deprecationMessages = [];

        set_error_handler(function (int $number, string $message) use (&$deprecationMessages): void {
            $deprecationMessages[] = $message;
        }, E_USER_DEPRECATED);

        $file = new MockSplFileInfo([]);

        $tokens = Tokens::fromCode($code);

        $fixers = $this->getFixers($rules, $indent, $lineEnding);

        $appliedFixers = [];

        foreach ($fixers as $fixer) {
            if (!$fixer->isCandidate($tokens) || !$fixer->supports($file)) {
                continue;
            }

            $fixer->fix($file, $tokens);

            if ($tokens->isChanged()) {
                $tokens->clearEmptyTokens();
                $tokens->clearChanged();

                $appliedFixers[] = $fixer;
            }
        }

        restore_error_handler();

        return new FixReport($tokens->generateCode(), $appliedFixers, $deprecationMessages);
    }

    /**
     * @return \PhpCsFixer\Fixer\FixerInterface[]
     */
    private function getFixers(
        array $rules,
        string $indent,
        LineEnding $lineEnding
    ): array {
        return $this->fixerFactory
            ->registerBuiltInFixers()
            ->setWhitespacesConfig(
                new WhitespacesFixerConfig($indent, $lineEnding->getReal())
            )
            ->useRuleSet(new RuleSet($rules))
            ->getFixers()
        ;
    }
}
