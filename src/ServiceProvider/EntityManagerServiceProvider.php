<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class EntityManagerServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        EntityManagerInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            EntityManagerInterface::class,
            $this->getEntityManager()
        );
    }

    private function getEntityManager(): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__.'/Entity'],
            false,
            null,
            new FilesystemCache(__DIR__.'/../../data/cache/doctrine'),
            false
        );

        $connection = [
            'url' => getenv('DB_URL'),
        ];

        return EntityManager::create($connection, $config);
    }
}
