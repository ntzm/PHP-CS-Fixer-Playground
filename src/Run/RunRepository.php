<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Run;

use Doctrine\ORM\EntityManagerInterface;
use Hashids\HashidsInterface;
use PhpCsFixerPlayground\Entity\Run;

final class RunRepository implements RunRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var HashidsInterface
     */
    private $hashids;

    public function __construct(
        EntityManagerInterface $entityManager,
        HashidsInterface $hashids
    ) {
        $this->entityManager = $entityManager;
        $this->hashids = $hashids;
    }

    public function getByHash(string $hash): Run
    {
        $id = $this->hashids->decode($hash)[0] ?? null;

        if (empty($id)) {
            throw RunNotFoundException::fromHash($hash);
        }

        $run = $this->entityManager->find(Run::class, $id);

        if (!$run instanceof Run) {
            throw RunNotFoundException::fromHash($hash);
        }

        return $run;
    }

    public function save(Run $run): void
    {
        $this->entityManager->persist($run);
        $this->entityManager->flush();
    }
}
