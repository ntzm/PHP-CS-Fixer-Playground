<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerPlayground\FixerConfigurationResolverWrapper;
use PhpCsFixerPlayground\FixerWrapper;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

/**
 * @covers \PhpCsFixerPlayground\FixerWrapper
 */
final class FixerWrapperTest extends TestCase
{
    public function testWrapsMethods(): void
    {
        $fixer = $this->createMock(FixerInterface::class);
        $fixer->method('isCandidate')->willReturn(true);
        $fixer->method('isRisky')->willReturn(true);
        $fixer->method('getName')->willReturn('foo_bar');
        $fixer->method('getPriority')->willReturn(5);
        $fixer->method('supports')->willReturn(true);

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isCandidate(new Tokens()));
        $this->assertTrue($wrapper->isRisky());
        $this->assertSame('foo_bar', $wrapper->getName());
        $this->assertSame(5, $wrapper->getPriority());
        $this->assertTrue($wrapper->supports(new MockSplFileInfo([])));
    }

    public function testDeprecated(): void
    {
        $fixer = $this->createMock(DeprecatedFixerInterface::class);
        $fixer->method('getSuccessorsNames')->willReturn(['bar_baz']);

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isDeprecated());
        $this->assertSame(['bar_baz'], $wrapper->getSuccessorsNames());
    }

    public function testNotDeprecated(): void
    {
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->assertFalse($wrapper->isDeprecated());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not deprecated');

        $wrapper->getSuccessorsNames();
    }

    public function testConfigurable(): void
    {
        $configurationDefinition = $this->createMock(FixerConfigurationResolverInterface::class);

        $fixer = $this->createMock(ConfigurationDefinitionFixerInterface::class);
        $fixer->method('getConfigurationDefinition')->willReturn($configurationDefinition);

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isConfigurable());
        $this->assertInstanceOf(FixerConfigurationResolverWrapper::class, $wrapper->getConfig());
    }

    public function testNotConfigurable(): void
    {
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->assertFalse($wrapper->isConfigurable());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not configurable');

        $wrapper->getConfig();
    }

    public function testDefined(): void
    {
        $definition = $this->createMock(FixerDefinitionInterface::class);

        $fixer = $this->createMock(DefinedFixerInterface::class);
        $fixer->method('getDefinition')->willReturn($definition);

        $wrapper = new FixerWrapper($fixer);

        $this->assertSame($definition, $wrapper->getDefinition());
    }

    public function testNotDefined(): void
    {
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not defined');

        $wrapper->getDefinition();
    }
}
