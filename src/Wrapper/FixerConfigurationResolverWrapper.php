<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

final class FixerConfigurationResolverWrapper implements FixerConfigurationResolverInterface
{
    /**
     * @var FixerConfigurationResolverInterface
     */
    private $resolver;

    public function __construct(FixerConfigurationResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getOptions(): array
    {
        return array_map(function (FixerOptionInterface $option): FixerOptionWrapper {
            return new FixerOptionWrapper($option);
        }, $this->resolver->getOptions());
    }

    public function resolve(array $configuration): array
    {
        return $this->resolver->resolve($configuration);
    }
}
