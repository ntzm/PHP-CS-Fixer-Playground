<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerPlayground\FixerConfigurationResolverWrapper;
use PhpCsFixerPlayground\FixerWrapper;
use PHPUnit\Framework\TestCase;
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

    public function testConfigurable(): void
    {
        $configurationDefinition = $this->createMock(FixerConfigurationResolverInterface::class);

        $fixer = $this->createMock(ConfigurationDefinitionFixerInterface::class);
        $fixer->method('getConfigurationDefinition')->willReturn($configurationDefinition);

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isConfigurable());
        $this->assertInstanceOf(FixerConfigurationResolverWrapper::class, $wrapper->getConfig());
    }
}
