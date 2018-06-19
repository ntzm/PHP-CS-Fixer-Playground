<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpVersion;

use InvalidArgumentException;
use PhpCsFixerPlayground\PhpVersion\PhpVersion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpVersion\PhpVersion
 */
final class PhpVersionTest extends TestCase
{
    /** @dataProvider provideValidPhpVersions */
    public function testValid(string $input): void
    {
        $version = new PhpVersion($input);

        $this->assertSame($input, (string) $version);
    }

    public function provideValidPhpVersions(): array
    {
        return [
            ['7.2.0'],
            ['7.1.2'],
            ['7.0.1'],
            ['5.6.1'],
        ];
    }

    /** @dataProvider provideInvalidPhpVersions */
    public function testInvalid(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid PHP version $input");

        new PhpVersion($input);
    }

    public function provideInvalidPhpVersions(): array
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
