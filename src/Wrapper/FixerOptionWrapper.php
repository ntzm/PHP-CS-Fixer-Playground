<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Wrapper;

use Closure;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOptionInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use RuntimeException;

final class FixerOptionWrapper implements FixerOptionInterface
{
    private const FORCE_ALLOW_ASSOC = [
        'php_unit_test_case_static_method_calls' => [
            'methods',
        ],
        'binary_operator_spaces' => [
            'operators',
        ],
    ];

    /** @var FixerOptionInterface */
    private $option;

    /** @var FixerInterface */
    private $fixer;

    public function __construct(
        FixerOptionInterface $option,
        FixerInterface $fixer
    ) {
        $this->option = $option;
        $this->fixer = $fixer;
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

    /** @return string[]|null */
    public function getAllowedTypes(): ?array
    {
        $allowedTypes = $this->option->getAllowedTypes();

        if ($allowedTypes === null) {
            $allowedValues = $this->getPrintableAllowedValues();

            if ($allowedValues === null) {
                return null;
            }

            $allowedTypes = $this->getTypesFromValues($allowedValues);
        }

        $arrayPosition = array_search('array', $allowedTypes, true);

        if ($arrayPosition !== false) {
            if (
                (isset(self::FORCE_ALLOW_ASSOC[$this->fixer->getName()]) && \in_array($this->getName(), self::FORCE_ALLOW_ASSOC[$this->fixer->getName()], true)) ||
                ($this->hasDefault() && \is_array($this->getDefault()) && $this->isAssociative($this->getDefault()))
            ) {
                $allowedTypes[$arrayPosition] = 'associative-array';
            }
        }

        sort($allowedTypes);

        return $allowedTypes;
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

    /** @return string[] */
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

    private function isAssociative(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }
}
