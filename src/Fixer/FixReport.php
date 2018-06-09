<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Fixer;

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

    /**
     * @var string[]
     */
    private $deprecationMessages;

    public function __construct(
        string $result,
        array $appliedFixers,
        array $deprecationMessages
    ) {
        $this->result = $result;
        $this->appliedFixers = $appliedFixers;
        $this->deprecationMessages = $deprecationMessages;
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

    /**
     * @return string[]
     */
    public function getDeprecationMessages(): array
    {
        return $this->deprecationMessages;
    }
}
