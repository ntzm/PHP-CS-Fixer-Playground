<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Wrapper;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixerPlayground\Wrapper\FixerCollection;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Wrapper\FixerCollection
 */
final class FixerCollectionTest extends TestCase
{
    public function testWithWhitespacesConfig(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        /** @var WhitespacesAwareFixerInterface|MockObject $whitespaceAwareFixer */
        $whitespaceAwareFixer = $this->createMock(WhitespacesAwareFixerInterface::class);
        $whitespaceAwareFixer
            ->expects($this->once())
            ->method('setWhitespacesConfig')
            ->with($this->callback(function (WhitespacesFixerConfig $config): bool {
                return $config->getIndent() === "\t"
                    && $config->getLineEnding() === "\r\n";
            }))
        ;

        $whitespacesConfig = new WhitespacesFixerConfig("\t", "\r\n");

        $fixers = new FixerCollection([
            new FixerWrapper($fixer),
            new FixerWrapper($whitespaceAwareFixer),
        ]);

        $fixers->withWhitespaceConfig($whitespacesConfig);
    }

    public function testIterable(): void
    {
        /** @var FixerInterface|MockObject $fixer */
        $fixer = $this->createMock(FixerInterface::class);

        /** @var WhitespacesAwareFixerInterface|MockObject $whitespaceAwareFixer */
        $whitespaceAwareFixer = $this->createMock(WhitespacesAwareFixerInterface::class);

        $wrappedFixers = [
            new FixerWrapper($fixer),
            new FixerWrapper($whitespaceAwareFixer),
        ];

        $fixers = new FixerCollection($wrappedFixers);

        $this->assertSame($wrappedFixers, iterator_to_array($fixers));
    }
}
