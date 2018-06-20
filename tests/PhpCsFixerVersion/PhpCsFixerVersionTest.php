<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use InvalidArgumentException;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersion
 */
final class PhpCsFixerVersionTest extends TestCase
{
    /** @dataProvider provideValidPhpCsFixerVersions */
    public function testValid(string $number, string $name): void
    {
        $version = new PhpCsFixerVersion($number, $name);

        $this->assertSame("$number $name", (string) $version);
        $this->assertSame($number, $version->getNumber());
        $this->assertSame($name, $version->getName());
    }

    public function provideValidPhpCsFixerVersions(): array
    {
        return [
            ['2.2.15', 'Foo'],
            ['2.11.1', 'Bar'],
            ['2.12.0', 'Baz'],
        ];
    }

    /** @dataProvider provideInvalidPhpCsFixerVersions */
    public function testInvalid(string $number): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid PHP-CS-Fixer version $number");

        new PhpCsFixerVersion($number, 'Long Journey');
    }

    public function provideInvalidPhpCsFixerVersions(): array
    {
        return [
            ['foo'],
            ['0'],
            ['7.2'],
            ['7.2.'],
            ['7.'],
        ];
    }
}
