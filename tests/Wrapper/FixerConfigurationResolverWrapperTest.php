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
        $resolver
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                $this->createMock(FixerOptionInterface::class),
                $this->createMock(FixerOptionInterface::class),
            ])
        ;

        $wrapper = new FixerConfigurationResolverWrapper($resolver);

        $this->assertContainsOnlyInstancesOf(FixerOptionWrapper::class, $wrapper->getOptions());
    }

    public function testResolve(): void
    {
        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('resolve')
            ->willReturn(['foo' => 'bar'])
        ;

        $wrapper = new FixerConfigurationResolverWrapper($resolver);

        $this->assertSame(['foo' => 'bar'], $wrapper->resolve([]));
    }

    public function testJsonSerialize(): void
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
            ->expects($this->exactly(2))
            ->method('getAllowedValues')
            ->willReturn(['baz', function (): void {}])
        ;

        $resolver = $this->createMock(FixerConfigurationResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([$option])
        ;

        $json = json_decode(json_encode(new FixerConfigurationResolverWrapper($resolver)), true);

        $this->assertSame([
            [
                'name' => 'foo',
                'description' => 'Bar.',
                'has_default' => false,
                'default' => null,
                'allowed_types' => ['string'],
                'allowed_values' => ['baz'],
            ],
        ], $json);
    }
}
