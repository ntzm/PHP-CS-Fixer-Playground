<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixerPlayground\Wrapper\FixerOptionWrapper;
use PHPUnit\Framework\TestCase;

final class FixerOptionWrapperTest extends TestCase
{
    public function testWrapsMethods(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo')
        ;
        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('foo bar')
        ;
        $option
            ->expects($this->once())
            ->method('hasDefault')
            ->willReturn(true)
        ;
        $option
            ->expects($this->once())
            ->method('getDefault')
            ->willReturn('baz')
        ;
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(['string'])
        ;
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn(['baz', 'bop'])
        ;
        $option
            ->expects($this->once())
            ->method('getNormalizer')
            ->willReturn(null)
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame('foo', $wrapper->getName());
        $this->assertSame('foo bar', $wrapper->getDescription());
        $this->assertTrue($wrapper->hasDefault());
        $this->assertSame('baz', $wrapper->getDefault());
        $this->assertSame(['string'], $wrapper->getAllowedTypes());
        $this->assertSame(['baz', 'bop'], $wrapper->getAllowedValues());
        $this->assertNull($wrapper->getNormalizer());
    }

    public function testGetAllowedTypesInferredFromValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(null)
        ;
        $option
            ->expects($this->exactly(2))
            ->method('getAllowedValues')
            ->willReturn(['foo', 'bar', ['baz'], true, function (): void {}])
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame(['array', 'bool', 'string'], $wrapper->getAllowedTypes());
    }

    public function getGetAllowedTypesInferredFromNullAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(null)
        ;
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn(null)
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertNull($wrapper->getAllowedTypes());
    }

    public function testGetPrintableAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->exactly(2))
            ->method('getAllowedValues')
            ->willReturn([
                'foo',
                'bar',
                function (): void {},
                'baz',
            ])
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame(['foo', 'bar', 'baz'], $wrapper->getPrintableAllowedValues());
    }

    public function testGetPrintableAllowedValuesNoAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn(null)
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertNull($wrapper->getPrintableAllowedValues());
    }

    public function testJsonSerializeWithDefault(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo')
        ;
        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Bar.')
        ;
        $option
            ->expects($this->exactly(2))
            ->method('hasDefault')
            ->willReturn(true)
        ;
        $option
            ->expects($this->once())
            ->method('getDefault')
            ->willReturn('baz')
        ;
        $option
            ->expects($this->exactly(4))
            ->method('getAllowedValues')
            ->willReturn(['baz', function (): void {}])
        ;

        $json = json_decode(json_encode(new FixerOptionWrapper($option)), true);

        $this->assertSame([
            'name' => 'foo',
            'description' => 'Bar.',
            'has_default' => true,
            'default' => 'baz',
            'allowed_types' => ['string'],
            'allowed_values' => ['baz'],
        ], $json);
    }

    public function testJsonSerializeWithoutDefault(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo')
        ;
        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Bar.')
        ;
        $option
            ->expects($this->exactly(2))
            ->method('hasDefault')
            ->willReturn(false)
        ;
        $option
            ->expects($this->exactly(4))
            ->method('getAllowedValues')
            ->willReturn(['baz', function (): void {}])
        ;

        $json = json_decode(json_encode(new FixerOptionWrapper($option)), true);

        $this->assertSame([
            'name' => 'foo',
            'description' => 'Bar.',
            'has_default' => false,
            'default' => null,
            'allowed_types' => ['string'],
            'allowed_values' => ['baz'],
        ], $json);
    }

    /**
     * @param bool       $expected
     * @param array|null $input
     *
     * @dataProvider provideTestAllowsMultipleValuesCases
     */
    public function testAllowsMultipleValues(bool $expected, ?array $input): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn($input)
        ;

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame($expected, $wrapper->allowsMultipleValues());
    }

    public function provideTestAllowsMultipleValuesCases(): array
    {
        return [
            [
                false,
                [],
            ],
            [
                false,
                null,
            ],
            [
                false,
                ['foo', 'bar'],
            ],
            [
                false,
                [function () {}],
            ],
            [
                true,
                [new AllowedValueSubset(['foo'])],
            ],
        ];
    }
}
