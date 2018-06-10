<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use Closure;
use JsonSerializable;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOptionInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use RuntimeException;

final class FixerOptionWrapper implements FixerOptionInterface, JsonSerializable
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

        $allowedValues = $this->getPrintableAllowedValues();

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

        if ($this->allowsMultipleValues()) {
            return $allowedValues[0]->getAllowedValues();
        }

        return array_values(array_filter($allowedValues, function ($value): bool {
            return !$value instanceof Closure;
        }));
    }

    public function getNormalizer(): ?Closure
    {
        return $this->option->getNormalizer();
    }

    public function allowsMultipleValues(): bool
    {
        $values = $this->getAllowedValues();

        return $values !== null
            && \count($values) === 1
            && $values[0] instanceof AllowedValueSubset;
    }

    public function isDeprecated(): bool
    {
        return $this->option instanceof DeprecatedFixerOptionInterface;
    }

    public function getDeprecationMessage(): string
    {
        if (!$this->option instanceof DeprecatedFixerOptionInterface) {
            throw new RuntimeException('Option not deprecated');
        }

        return $this->option->getDeprecationMessage();
    }

    private function getTypesFromValues(array $values): array
    {
        $types = array_values(array_unique(array_map(function ($value): string {
            $type = strtolower(\gettype($value));

            if ($type === 'boolean') {
                return 'bool';
            }

            return $type;
        }, $values)));

        sort($types);

        return $types;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'has_default' => $this->hasDefault(),
            'default' => $this->hasDefault() ? $this->getDefault() : null,
            'allowed_types' => $this->getAllowedTypes(),
            'allowed_values' => $this->getPrintableAllowedValues(),
        ];
    }
}
