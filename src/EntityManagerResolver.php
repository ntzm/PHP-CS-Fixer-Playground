<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerResolver
{
    public function __invoke(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__.'/Entity'],
            true,
            null,
            null,
            false
        );

        $connection = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__.'/../database.sqlite',
        ];

        return EntityManager::create($connection, $config);
    }
}
