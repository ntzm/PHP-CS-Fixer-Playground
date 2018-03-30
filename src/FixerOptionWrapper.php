<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixer\FixerConfiguration\FixerOptionInterface;

final class FixerOptionWrapper implements FixerOptionInterface
{
    /**
     * @var FixerOptionInterface
     */
    private $option;

    public function __construct(FixerOptionInterface $option)
    {
        $this->option = $option;
    }

    public function getName(): string
    {
        return $this->option->getName();
    }

    public function getDescription(): string
    {
        return $this->option->getDescription();
    }

    public function hasDefault(): bool
    {
        return $this->option->hasDefault();
    }

    public function getDefault()
    {
        return $this->option->getDefault();
    }

    public function getAllowedTypes()
    {
        return $this->option->getAllowedTypes();
    }

    public function getAllowedValues()
    {
        return $this->option->getAllowedValues();
    }

    public function getNormalizer()
    {
        return $this->option->getNormalizer();
    }
}
