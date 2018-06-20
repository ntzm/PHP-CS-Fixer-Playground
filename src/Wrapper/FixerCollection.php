<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use ArrayIterator;
use IteratorAggregate;
use PhpCsFixer\WhitespacesFixerConfig;

final class FixerCollection implements IteratorAggregate
{
    /** @var FixerWrapper[] */
    private $fixerWrappers;

    public function __construct(array $fixerWrappers)
    {
        $this->fixerWrappers = $fixerWrappers;
    }

    public function withWhitespaceConfig(WhitespacesFixerConfig $config): self
    {
        $fixersWithConfiguredWhitespace = [];

        foreach ($this->fixerWrappers as $fixerWrapper) {
            $newFixerWrapper = clone $fixerWrapper;

            if ($newFixerWrapper->isAwareOfWhitespaceConfig()) {
                $newFixerWrapper->setWhitespacesConfig($config);
            }

            $fixersWithConfiguredWhitespace[] = $newFixerWrapper;
        }

        return new self($fixersWithConfiguredWhitespace);
    }

    /** @return ArrayIterator|FixerWrapper[] */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->fixerWrappers);
    }
}
