<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Run;

use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\LineEnding;
use PhpCsFixerPlayground\Run\RunNotFoundException;
use PhpCsFixerPlayground\Run\RunRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @covers \PhpCsFixerPlayground\Run\RunRepository
 */
final class RunRepositoryTest extends TestCase
{
    public function testGetByUuid(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote' => true], '    ', LineEnding::fromVisible('\n'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Run::class, 'b05e6923-a749-4ec0-afc1-cbafc2452c5f')
            ->willReturn($run)
        ;

        $runs = new RunRepository($entityManager);

        $this->assertSame($run, $runs->findByUuid(Uuid::fromString('b05e6923-a749-4ec0-afc1-cbafc2452c5f')));
    }

    public function testGetByUuidNonExistent(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Run::class, 'b05e6923-a749-4ec0-afc1-cbafc2452c5f')
            ->willReturn(null)
        ;

        $runs = new RunRepository($entityManager);

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with UUID b05e6923-a749-4ec0-afc1-cbafc2452c5f');

        $runs->findByUuid(Uuid::fromString('b05e6923-a749-4ec0-afc1-cbafc2452c5f'));
    }

    public function testSave(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote' => true], '    ', LineEnding::fromVisible('\n'));

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

        $runs = new RunRepository($entityManager);

        $runs->save($run);
    }
}
