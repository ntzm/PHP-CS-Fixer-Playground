<?php

namespace PhpCsFixerPlayground;

use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use RuntimeException;
use SplFileInfo;

final class SupportFixerWrapper implements DefinedFixerInterface
{
    /**
     * @var DefinedFixerInterface
     */
    private $fixer;

    public function __construct(DefinedFixerInterface $fixer)
    {
        $this->fixer = $fixer;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $this->fixer->isCandidate($tokens);
    }

    public function isRisky(): bool
    {
        return $this->fixer->isRisky();
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $this->fixer->fix($file, $tokens);
    }

    public function getName(): string
    {
        return $this->fixer->getName();
    }

    public function getPriority(): int
    {
        return $this->fixer->getPriority();
    }

    public function supports(SplFileInfo $file): bool
    {
        return $this->fixer->supports($file);
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return $this->fixer->getDefinition();
    }

    public function isDeprecated(): bool
    {
        return $this->fixer instanceof DeprecatedFixerInterface;
    }

    public function isConfigurable(): bool
    {
        return $this->fixer instanceof ConfigurationDefinitionFixerInterface;
    }

    public function getConfig(): FixerConfigurationResolverInterface
    {
        if (!$this->fixer instanceof ConfigurationDefinitionFixerInterface) {
            throw new RuntimeException('Fixer not configurable');
        }

        return $this->fixer->getConfigurationDefinition();
    }
}
