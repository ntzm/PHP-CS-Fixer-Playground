<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixerPlayground\Wrapper\FixerConfigurationResolverWrapper;
use PhpCsFixerPlayground\Wrapper\FixerOptionWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Wrapper\FixerConfigurationResolverWrapper
 */
final class FixerConfigurationResolverWrapperTest extends TestCase
{
    public function testGetOptions(): void
    {
        /** @var FixerConfigurationResolverInterface|MockObject $resolver */
        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                $this->createMock(FixerOptionInterface::class),
                $this->createMock(FixerOptionInterface::class),
            ])
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerConfigurationResolverWrapper($resolver, $fixer);

        $this->assertContainsOnlyInstancesOf(FixerOptionWrapper::class, $wrapper->getOptions());
    }

    public function testResolve(): void
    {
        /** @var FixerConfigurationResolverInterface|MockObject $resolver */
        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(['foo' => 'bar'])
        ;

        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        $wrapper = new FixerConfigurationResolverWrapper($resolver, $fixer);

        $this->assertSame(['foo' => 'bar'], $wrapper->resolve([]));
    }
}
