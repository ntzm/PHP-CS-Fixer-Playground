<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fix;

use PhpCsFixer\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\Wrapper\FixerCollectionFactoryInterface;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

final class Fix implements FixInterface
{
    /** @var FixerCollectionFactoryInterface */
    private $fixerCollectionFactory;

    public function __construct(
        FixerCollectionFactoryInterface $fixerCollectionFactory
    ) {
        $this->fixerCollectionFactory = $fixerCollectionFactory;
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

        $fixers = $this->fixerCollectionFactory
            ->fromRuleSet(new RuleSet($rules))
            ->withWhitespaceConfig(
                new WhitespacesFixerConfig((string) $indent, $lineEnding->getReal()),
            )
        ;

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
}
