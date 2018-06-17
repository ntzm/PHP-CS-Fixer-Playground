<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOptionInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixerPlayground\Wrapper\FixerOptionWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class FixerOptionWrapperTest extends TestCase
{
    public function testWrapsMethods(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
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

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

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
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(null)
        ;
        $option
            ->expects($this->exactly(2))
            ->method('getAllowedValues')
            ->willReturn(['foo', 'bar', true, function (): void {}])
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertSame(['bool', 'string'], $wrapper->getAllowedTypes());
    }

    public function testGetAllowedTypesInferredFromNullAllowedValues(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
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

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertNull($wrapper->getAllowedTypes());
    }

    public function testGetAllowedTypesAssociativeDefault(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(['array', 'string'])
        ;
        $option
            ->expects($this->once())
            ->method('hasDefault')
            ->willReturn(true)
        ;
        $option
            ->expects($this->exactly(2))
            ->method('getDefault')
            ->willReturn(['foo' => 'bar'])
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertSame(['associative-array', 'string'], $wrapper->getAllowedTypes());
    }

    /** @dataProvider provideForcedOptions */
    public function testGetAllowedTypesAssociativeForced(
        string $fixerName,
        string $optionName
    ): void {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getName')
            ->willReturn($optionName)
        ;
        $option
            ->expects($this->once())
            ->method('getAllowedTypes')
            ->willReturn(['array', 'string'])
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);
        $fixer
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($fixerName)
        ;

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertSame(['associative-array', 'string'], $wrapper->getAllowedTypes());
    }

    public function provideForcedOptions(): array
    {
        return [
            ['php_unit_test_case_static_method_calls', 'methods'],
            ['binary_operator_spaces', 'operators'],
        ];
    }

    public function testGetPrintableAllowedValues(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
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

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertSame(['foo', 'bar', 'baz'], $wrapper->getPrintableAllowedValues());
    }

    public function testGetPrintableAllowedValuesNoAllowedValues(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn(null)
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertNull($wrapper->getPrintableAllowedValues());
    }

    /**
     * @param bool       $expected
     * @param array|null $input
     *
     * @dataProvider provideTestAllowsMultipleValuesCases
     */
    public function testAllowsMultipleValues(bool $expected, ?array $input): void
    {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getAllowedValues')
            ->willReturn($input)
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

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
                [function (): void {}],
            ],
            [
                true,
                [new AllowedValueSubset(['foo'])],
            ],
        ];
    }

    public function testIsDeprecatedFalse(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertFalse($wrapper->isDeprecated());
    }

    public function testIsDeprecatedTrue(): void
    {
        /** @var DeprecatedFixerOptionInterface|MockObject $option */
        $option = $this->createMock(DeprecatedFixerOptionInterface::class);

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertTrue($wrapper->isDeprecated());
    }

    public function testGetDeprecationMessageNotDeprecated(): void
    {
        /** @var FixerOptionInterface|MockObject $option */
        $option = $this->createMock(FixerOptionInterface::class);

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Option not deprecated');

        $wrapper->getDeprecationMessage();
    }

    public function testGetDeprecationMessage(): void
    {
        /** @var DeprecatedFixerOptionInterface|MockObject $option */
        $option = $this->createMock(DeprecatedFixerOptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getDeprecationMessage')
            ->willReturn('foo bar')
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerOptionWrapper($option, $fixer);

        $this->assertSame('foo bar', $wrapper->getDeprecationMessage());
    }
}
