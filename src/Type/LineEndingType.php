<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PhpCsFixerPlayground\LineEnding;

final class LineEndingType extends Type
{
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 4]);
    }

    /** @param string $value */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): LineEnding {
        return LineEnding::fromVisible($value);
    }

    /** @param LineEnding $value */
    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): string {
        return $value->getVisible();
    }

    public function getName(): string
    {
        return 'line_ending';
    }
}
