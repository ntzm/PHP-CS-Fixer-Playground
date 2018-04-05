<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Run;

use Doctrine\ORM\EntityManagerInterface;
use Hashids\HashidsInterface;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PhpCsFixerPlayground\Run\RunRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Run\RunRepository
 */
final class RunRepositoryTest extends TestCase
{
    public function testGetByHash(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote' => true], '    ', '\n');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Run::class, 5)
            ->willReturn($run)
        ;

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids
            ->expects($this->once())
            ->method('decode')
            ->with('foo')
            ->willReturn([5])
        ;

        $runs = new RunRepository($entityManager, $hashids);

        $this->assertSame($run, $runs->getByHash('foo'));
    }

    public function testGetByHashNonExistent(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Run::class, 5)
            ->willReturn(null)
        ;

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids
            ->expects($this->once())
            ->method('decode')
            ->with('foo')
            ->willReturn([5])
        ;

        $runs = new RunRepository($entityManager, $hashids);

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with hash foo');

        $runs->getByHash('foo');
    }

    public function testGetByHashInvalid(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids
            ->expects($this->once())
            ->method('decode')
            ->with('foo')
            ->willReturn([])
        ;

        $runs = new RunRepository($entityManager, $hashids);

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with hash foo');

        $runs->getByHash('foo');
    }

    public function testSave(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote' => true], '    ', '\n');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($run)
        ;
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;

        $hashids = $this->createMock(HashidsInterface::class);

        $runs = new RunRepository($entityManager, $hashids);

        $runs->save($run);
    }
}
