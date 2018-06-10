<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PhpCsFixerPlayground\Type\LineEndingType;

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
            'driver' => 'pdo_sqlite',
            'path' => __DIR__.'/../database.sqlite',
        ];

        if (!Type::hasType('line_ending')) {
            Type::addType('line_ending', LineEndingType::class);
        }

        return EntityManager::create($connection, $config);
    }
}