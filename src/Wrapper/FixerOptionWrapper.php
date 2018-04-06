<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use Closure;
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

    /**
     * @return string[]|null
     */
    public function getAllowedTypes(): ?array
    {
        $allowedTypes = $this->option->getAllowedTypes();

        if ($allowedTypes !== null) {
            sort($allowedTypes);

            return $allowedTypes;
        }

        $allowedValues = $this->getAllowedValues();

        if ($allowedValues === null) {
            return null;
        }

        return $this->getTypesFromValues($allowedValues);
    }

    public function getAllowedValues(): ?array
    {
        return $this->option->getAllowedValues();
    }

    public function getPrintableAllowedValues(): ?array
    {
        $allowedValues = $this->getAllowedValues();

        if ($allowedValues === null) {
            return null;
        }

        return array_values(array_filter($allowedValues, function ($value): bool {
            return !$value instanceof Closure;
        }));
    }

    public function getNormalizer(): ?Closure
    {
        return $this->option->getNormalizer();
    }

    private function getTypesFromValues(array $values): array
    {
        $types = array_keys(array_count_values(array_map(function ($value): string {
            $type = strtolower(gettype($value));

            if ($type === 'boolean') {
                return 'bool';
            }

            return $type;
        }, $values)));

        sort($types);

        return $types;
    }
}
