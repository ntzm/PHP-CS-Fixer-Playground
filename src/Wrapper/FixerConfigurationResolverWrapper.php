<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

final class FixerConfigurationResolverWrapper implements FixerConfigurationResolverInterface
{
    /** @var FixerConfigurationResolverInterface */
    private $resolver;

    /** @var FixerInterface */
    private $fixer;

    public function __construct(
        FixerConfigurationResolverInterface $resolver,
        FixerInterface $fixer
    ) {
        $this->resolver = $resolver;
        $this->fixer = $fixer;
    }

    /** @return FixerOptionWrapper[] */
    public function getOptions(): array
    {
        return array_map(function (FixerOptionInterface $option): FixerOptionWrapper {
            return new FixerOptionWrapper($option, $this->fixer);
        }, $this->resolver->getOptions());
    }

    public function resolve(array $configuration): array
    {
        return $this->resolver->resolve($configuration);
    }
}
