<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\Fixer\ConfigurationDefinitionFixerInterface;
use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerPlayground\Wrapper\FixerConfigurationResolverWrapper;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

/**
 * @covers \PhpCsFixerPlayground\Wrapper\FixerWrapper
 */
final class FixerWrapperTest extends TestCase
{
    public function testWrapsMethods(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);
        $fixer
            ->expects($this->once())
            ->method('isCandidate')
            ->with($this->isInstanceOf(Tokens::class))
            ->willReturn(true)
        ;
        $fixer
            ->expects($this->once())
            ->method('isRisky')
            ->willReturn(true)
        ;
        $fixer
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo_bar')
        ;
        $fixer
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn(5)
        ;
        $fixer
            ->expects($this->once())
            ->method('supports')
            ->with($this->isInstanceOf(MockSplFileInfo::class))
            ->willReturn(true)
        ;
        $fixer
            ->expects($this->once())
            ->method('fix')
            ->with(
                $this->isInstanceOf(MockSplFileInfo::class),
                $this->isInstanceOf(Tokens::class)
            )
        ;

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isCandidate(new Tokens()));
        $this->assertTrue($wrapper->isRisky());
        $this->assertSame('foo_bar', $wrapper->getName());
        $this->assertSame(5, $wrapper->getPriority());
        $this->assertTrue($wrapper->supports(new MockSplFileInfo([])));

        $wrapper->fix(new MockSplFileInfo([]), new Tokens());
    }

    public function testDeprecated(): void
    {
        /** @var DeprecatedFixerInterface|MockObject $fixer */
        $fixer = $this->createMock(DeprecatedFixerInterface::class);
        $fixer
            ->expects($this->once())
            ->method('getSuccessorsNames')
            ->willReturn(['bar_baz'])
        ;

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isDeprecated());
        $this->assertSame(['bar_baz'], $wrapper->getSuccessorsNames());
    }

    public function testNotDeprecated(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->assertFalse($wrapper->isDeprecated());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not deprecated');

        $wrapper->getSuccessorsNames();
    }

    public function testConfigurable(): void
    {
        /** @var FixerConfigurationResolverInterface|MockObject $configurationDefinition */
        $configurationDefinition = $this->createMock(FixerConfigurationResolverInterface::class);

        /** @var ConfigurationDefinitionFixerInterface|MockObject $fixer */
        $fixer = $this->createMock(ConfigurationDefinitionFixerInterface::class);
        $fixer
            ->expects($this->once())
            ->method('getConfigurationDefinition')
            ->willReturn($configurationDefinition)
        ;

        $wrapper = new FixerWrapper($fixer);

        $this->assertTrue($wrapper->isConfigurable());
        $this->assertInstanceOf(FixerConfigurationResolverWrapper::class, $wrapper->getConfig());
    }

    public function testNotConfigurable(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->assertFalse($wrapper->isConfigurable());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not configurable');

        $wrapper->getConfig();
    }

    public function testDefined(): void
    {
        /** @var FixerDefinitionInterface|MockObject $definition */
        $definition = $this->createMock(FixerDefinitionInterface::class);

        /** @var DefinedFixerInterface|MockObject $fixer */
        $fixer = $this->createMock(DefinedFixerInterface::class);
        $fixer
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition)
        ;

        $wrapper = new FixerWrapper($fixer);

        $this->assertSame($definition, $wrapper->getDefinition());
    }

    public function testNotDefined(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerWrapper($fixer);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fixer not defined');

        $wrapper->getDefinition();
    }

    public function testJsonSerializeRisky(): void
    {
        /** @var FixerDefinitionInterface|MockObject $definition */
        $definition = $this->createMock(FixerDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getSummary')
            ->willReturn('Bar foo.')
        ;
        $definition
            ->expects($this->once())
            ->method('getRiskyDescription')
            ->willReturn('Foo bar.')
        ;

        /** @var DefinedFixerInterface|MockObject $fixer */
        $fixer = $this->createMock(DefinedFixerInterface::class);
        $fixer
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo_bar')
        ;
        $fixer
            ->expects($this->once())
            ->method('isRisky')
            ->willReturn(true)
        ;
        $fixer
            ->expects($this->exactly(2))
            ->method('getDefinition')
            ->willReturn($definition)
        ;

        $json = json_decode(json_encode(new FixerWrapper($fixer)), true);

        $this->assertSame([
            'name' => 'foo_bar',
            'summary' => 'Bar foo.',
            'is_risky' => true,
            'risky_description' => 'Foo bar.',
            'is_deprecated' => false,
            'successors_names' => null,
            'is_configurable' => false,
            'config' => null,
        ], $json);
    }
}
