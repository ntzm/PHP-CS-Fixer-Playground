<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixerPlayground\Wrapper\FixerConfigurationResolverWrapper;
use PhpCsFixerPlayground\Wrapper\FixerOptionWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Wrapper\FixerConfigurationResolverWrapper
 */
final class FixerConfigurationResolverWrapperTest extends TestCase
{
    public function testGetOptions(): void
    {
        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver->method('getOptions')->willReturn([
            $this->createMock(FixerOptionInterface::class),
            $this->createMock(FixerOptionInterface::class),
        ]);

        $wrapper = new FixerConfigurationResolverWrapper($resolver);

        $this->assertContainsOnlyInstancesOf(FixerOptionWrapper::class, $wrapper->getOptions());
    }

    public function testResolve(): void
    {
        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver->method('resolve')->willReturn(['foo' => 'bar']);

        $wrapper = new FixerConfigurationResolverWrapper($resolver);

        $this->assertSame(['foo' => 'bar'], $wrapper->resolve([]));
    }
}
