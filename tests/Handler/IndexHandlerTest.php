<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Handler;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Handler\IndexHandler;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Handler\IndexHandler
 */
final class IndexHandlerTest extends TestCase
{
    public function test(): void
    {
        /** @var ViewFactoryInterface|MockObject $viewFactory */
        $viewFactory = $this->createMock(ViewFactoryInterface::class);
        $viewFactory
            ->expects($this->once())
            ->method('make')
            ->with(
                $this->callback(function (Run $run): bool {
                    return $run->getCode() === "<?php\n\n"
                        && $run->getRules() === []
                        && (string) $run->getIndent() === '    '
                        && $run->getLineEnding()->getVisible() === '\n';
                }),
                "<?php\n\n",
                [],
                [],
                $this->isInstanceOf(ConfigFile::class)
            )
            ->willReturn('foo')
        ;

        $handler = new IndexHandler($viewFactory);

        $response = $handler->__invoke([]);

        $this->assertSame('foo', $response->getContent());
    }
}
