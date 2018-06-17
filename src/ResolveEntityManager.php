<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

final class ResolveEntityManager
{
    public function __invoke(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__.'/Entity'],
            true,
            null,
            new FilesystemCache(__DIR__.'/../data/cache/doctrine'),
            false
        );

        $connection = [
            'url' => getenv('DB_URL'),
        ];

        return EntityManager::create($connection, $config);
    }
}
