<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;

final class PhpCsFixerVersionRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function has(string $version): bool
    {
        return (bool) $this->entityManager->find(PhpCsFixerVersion::class, $version);
    }

    public function save(PhpCsFixerVersion $version): void
    {
        $this->entityManager->persist($version);
        $this->entityManager->flush();
    }
}
