<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Handler;

use Exception;
use PhpCsFixer\Fixer\FixerInterface as PhpCsFixerFixerInterface;
use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Fix\FixInterface;
use PhpCsFixerPlayground\Fix\FixReport;
use PhpCsFixerPlayground\Handler\GetRunHandler;
use PhpCsFixerPlayground\Issue;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PhpCsFixerPlayground\Run\RunRepositoryInterface;
use PhpCsFixerPlayground\UrlGeneratorInterface;
use PhpCsFixerPlayground\View\ViewFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * @covers \PhpCsFixerPlayground\Handler\GetRunHandler
 */
final class GetRunHandlerTest extends TestCase
{
    public function test(): void
    {
        $runUuid = '4e3d4d6b-dd7d-401a-aacd-0d44fca19ae1';

        $run = new Run(
            '<?php echo "hi";',
            ['foo' => true],
            '    ',
            LineEnding::fromVisible('\n')
        );

        /** @var PhpCsFixerFixerInterface|MockObject $fooFixer */
        $fooFixer = $this->createMock(PhpCsFixerFixerInterface::class);

        $fixReport = new FixReport(
            "<?php echo 'hi';",
            [$fooFixer],
            ['do not use this function']
        );

        /** @var RunRepositoryInterface|MockObject $runs */
        $runs = $this->createMock(RunRepositoryInterface::class);
        $runs
            ->expects($this->once())
            ->method('findByUuid')
            ->with($this->callback(function (UuidInterface $uuid) use ($runUuid): bool {
                return $uuid->toString() === $runUuid;
            }))
            ->willReturn($run)
        ;

        /** @var ViewFactoryInterface|MockObject $viewFactory */
        $viewFactory = $this->createMock(ViewFactoryInterface::class);
        $viewFactory
            ->expects($this->once())
            ->method('make')
            ->with(
                $run,
                "<?php echo 'hi';",
                [$fooFixer],
                ['do not use this function'],
                $this->isInstanceOf(ConfigFile::class),
                $this->isInstanceOf(Issue::class)
            )
            ->willReturn('foo')
        ;

        /** @var FixInterface|MockObject $fix */
        $fix = $this->createMock(FixInterface::class);
        $fix
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                '<?php echo "hi";',
                ['foo' => true],
                '    ',
                $this->callback(function (LineEnding $lineEnding): bool {
                    return $lineEnding->getVisible() === '\n';
                })
            )
            ->willReturn($fixReport)
        ;

        /** @var UrlGeneratorInterface|MockObject $urlGenerator */
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator
            ->expects($this->once())
            ->method('generateUrlForRun')
            ->with($run)
            ->willReturn('/run/foo')
        ;

        $handler = new GetRunHandler($runs, $viewFactory, $fix, $urlGenerator);

        $response = $handler(['uuid' => $runUuid]);

        $this->assertSame('foo', $response->getContent());
    }

    public function testHandlesExceptionsInFix(): void
    {
        $runUuid = '4e3d4d6b-dd7d-401a-aacd-0d44fca19ae1';

        $run = new Run(
            '<?php echo "hi";',
            ['foo' => true],
            '    ',
            LineEnding::fromVisible('\n')
        );

        /** @var RunRepositoryInterface|MockObject $runs */
        $runs = $this->createMock(RunRepositoryInterface::class);
        $runs
            ->expects($this->once())
            ->method('findByUuid')
            ->with($this->callback(function (UuidInterface $uuid) use ($runUuid): bool {
                return $uuid->toString() === $runUuid;
            }))
            ->willReturn($run)
        ;

        /** @var ViewFactoryInterface|MockObject $viewFactory */
        $viewFactory = $this->createMock(ViewFactoryInterface::class);
        $viewFactory
            ->expects($this->once())
            ->method('make')
            ->with(
                $run,
                'bar',
                [],
                [],
                $this->isInstanceOf(ConfigFile::class),
                $this->isInstanceOf(Issue::class)
            )
            ->willReturn('foo')
        ;

        /** @var FixInterface|MockObject $fixer */
        $fixer = $this->createMock(FixInterface::class);
        $fixer
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                '<?php echo "hi";',
                ['foo' => true],
                '    ',
                $this->callback(function (LineEnding $lineEnding): bool {
                    return $lineEnding->getVisible() === '\n';
                })
            )
            ->willThrowException(new Exception('bar'))
        ;

        /** @var UrlGeneratorInterface|MockObject $urlGenerator */
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator
            ->expects($this->once())
            ->method('generateUrlForRun')
            ->with($run)
            ->willReturn('/run/foo')
        ;

        $handler = new GetRunHandler($runs, $viewFactory, $fixer, $urlGenerator);

        $response = $handler(['uuid' => $runUuid]);

        $this->assertSame('foo', $response->getContent());
    }

    public function testThrowsNotFoundOnInvalidUuid(): void
    {
        /** @var RunRepositoryInterface|MockObject $runs */
        $runs = $this->createMock(RunRepositoryInterface::class);

        /** @var ViewFactoryInterface|MockObject $viewFactory */
        $viewFactory = $this->createMock(ViewFactoryInterface::class);

        /** @var FixInterface|MockObject $fix */
        $fix = $this->createMock(FixInterface::class);

        /** @var UrlGeneratorInterface|MockObject $urlGenerator */
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $handler = new GetRunHandler($runs, $viewFactory, $fix, $urlGenerator);

        $this->expectException(RunNotFoundException::class);

        $handler(['uuid' => 'wqlkdj']);
    }
}
