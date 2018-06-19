<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fix;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

final class Fix implements FixInterface
{
    /** @var FixerFactory */
    private $fixerFactory;

    public function __construct(FixerFactory $fixerFactory)
    {
        $this->fixerFactory = $fixerFactory;
    }

    public function __invoke(
        string $code,
        array $rules,
        Indent $indent,
        LineEnding $lineEnding
    ): FixReport {
        $deprecationMessages = [];

        set_error_handler(function (int $number, string $message) use (&$deprecationMessages): bool {
            $deprecationMessages[] = $message;

            return true;
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

    /** @return FixerInterface[] */
    private function getFixers(
        array $rules,
        Indent $indent,
        LineEnding $lineEnding
    ): array {
        return $this->fixerFactory
            ->registerBuiltInFixers()
            ->setWhitespacesConfig(
                new WhitespacesFixerConfig(
                    (string) $indent,
                    $lineEnding->getReal()
                )
            )
            ->useRuleSet(new RuleSet($rules))
            ->getFixers()
        ;
    }
}
