<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use Doctrine\ORM\EntityManagerInterface;
use PhpCsFixerPlayground\Entity\Run;
use Ramsey\Uuid\UuidInterface;

final class RunRepository implements RunRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByUuid(UuidInterface $uuid): Run
    {
        $run = $this->entityManager->find(Run::class, $uuid->toString());

        if (!$run instanceof Run) {
            throw RunNotFoundException::fromUuid($uuid);
        }

        return $run;
    }

    public function save(Run $run): void
    {
        $this->entityManager->persist($run);
        $this->entityManager->flush();
    }
}
