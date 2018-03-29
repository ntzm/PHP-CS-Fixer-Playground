<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class FixReport
{
    /**
     * @var string
     */
    private $result;

    /**
     * @var \PhpCsFixer\Fixer\FixerInterface[]
     */
    private $appliedFixers;

    public function __construct(string $result, array $appliedFixers)
    {
        $this->result = $result;
        $this->appliedFixers = $appliedFixers;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return \PhpCsFixer\Fixer\FixerInterface[]
     */
    public function getAppliedFixers(): array
    {
        return $this->appliedFixers;
    }
}
