<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixerPlayground\Wrapper\FixerOptionWrapper;
use PHPUnit\Framework\TestCase;

final class FixerOptionWrapperTest extends TestCase
{
    public function testWrapsMethods(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option->method('getName')->willReturn('foo');
        $option->method('getDescription')->willReturn('foo bar');
        $option->method('hasDefault')->willReturn(true);
        $option->method('getDefault')->willReturn('baz');
        $option->method('getAllowedTypes')->willReturn(['string']);
        $option->method('getAllowedValues')->willReturn(['baz', 'bop']);
        $option->method('getNormalizer')->willReturn(null);

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
        $option->method('getAllowedTypes')->willReturn(null);
        $option->method('getAllowedValues')->willReturn(['foo', 'bar', ['baz'], true]);

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame(['array', 'bool', 'string'], $wrapper->getAllowedTypes());
    }

    public function getGetAllowedTypesInferredFromNullAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option->method('getAllowedTypes')->willReturn(null);
        $option->method('getAllowedValues')->willReturn(null);

        $wrapper = new FixerOptionWrapper($option);

        $this->assertNull($wrapper->getAllowedTypes());
    }

    public function testGetPrintableAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option->method('getAllowedValues')->willReturn([
            'foo',
            'bar',
            function (): void {},
            'baz',
        ]);

        $wrapper = new FixerOptionWrapper($option);

        $this->assertSame(['foo', 'bar', 'baz'], $wrapper->getPrintableAllowedValues());
    }

    public function testGetPrintableAllowedValuesNoAllowedValues(): void
    {
        $option = $this->createMock(FixerOptionInterface::class);
        $option->method('getAllowedValues')->willReturn(null);

        $wrapper = new FixerOptionWrapper($option);

        $this->assertNull($wrapper->getPrintableAllowedValues());
    }
}
