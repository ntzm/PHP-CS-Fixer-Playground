<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use RuntimeException;
use SplFileInfo;

final class FixerWrapper implements FixerInterface
{
    /** @var FixerInterface */
    private $fixer;

    public function __construct(FixerInterface $fixer)
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
        if (!$this->fixer instanceof DefinedFixerInterface) {
            throw new RuntimeException('Fixer not defined');
        }

        return $this->fixer->getDefinition();
    }

    public function isDeprecated(): bool
    {
        return $this->fixer instanceof DeprecatedFixerInterface;
    }

    /** @return string[] */
    public function getSuccessorsNames(): array
    {
        if (!$this->fixer instanceof DeprecatedFixerInterface) {
            throw new RuntimeException('Fixer not deprecated');
        }

        return $this->fixer->getSuccessorsNames();
    }

    public function isConfigurable(): bool
    {
        return $this->fixer instanceof ConfigurationDefinitionFixerInterface;
    }

    public function getConfig(): FixerConfigurationResolverWrapper
    {
        if (!$this->fixer instanceof ConfigurationDefinitionFixerInterface) {
            throw new RuntimeException('Fixer not configurable');
        }

        return new FixerConfigurationResolverWrapper(
            $this->fixer->getConfigurationDefinition(),
            $this
        );
    }
}
