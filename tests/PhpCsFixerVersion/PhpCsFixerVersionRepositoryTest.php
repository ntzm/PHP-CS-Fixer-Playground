<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepository
 */
final class PhpCsFixerVersionRepositoryTest extends TestCase
{
    public function testHas(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->exactly(2))
            ->method('find')
            ->withConsecutive(
                [PhpCsFixerVersion::class, '2.11.1'],
                [PhpCsFixerVersion::class, '2.11.0']
            )
            ->willReturnOnConsecutiveCalls(
                new PhpCsFixerVersion('2.11.1'),
                null
            )
        ;

        $versions = new PhpCsFixerVersionRepository($entityManager);

        $this->assertTrue($versions->has('2.11.1'));
        $this->assertFalse($versions->has('2.11.0'));
    }

    public function testSave(): void
    {
        $version = new PhpCsFixerVersion('2.11.1');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($version)
        ;
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;

        $versions = new PhpCsFixerVersionRepository($entityManager);

        $versions->save($version);
    }
}
