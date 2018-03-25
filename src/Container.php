<?php

namespace PhpCsFixerPlayground;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use League\Container\Container as BaseContainer;
use League\Container\ReflectionContainer;
use PDO;
use Symfony\Component\HttpFoundation\Request;

final class Container
{
    private $base;

    public function __construct()
    {
        $this->base = new BaseContainer();
        $this->base->delegate(new ReflectionContainer());

        $this->registerRequest();
        $this->registerHashids();
        $this->registerPdo();
        $this->registerRunRepository();
    }

    public function get(string $alias): object
    {
        return $this->base->get($alias);
    }

    private function registerRequest(): void
    {
        $this->base->add(Request::class, function (): Request {
            return Request::createFromGlobals();
        });
    }

    private function registerHashids(): void
    {
        $this->base->add(HashidsInterface::class, function (): HashidsInterface {
            return new Hashids(getenv('HASHIDS_SECRET'), 10);
        });
    }

    private function registerPdo(): void
    {
        $this->base->add(PDO::class, function (): PDO {
            $db = new PDO(
                sprintf('sqlite:%s', __DIR__.'/../database.sqlite'), null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );

            $db->exec('create table if not exists runs (id integer primary key autoincrement, code text not null, rules json not null)');

            return $db;
        });
    }

    private function registerRunRepository(): void
    {
        $this->base
            ->add(RunRepositoryInterface::class, RunRepository::class)
            ->withArgument(PDO::class)
            ->withArgument(HashidsInterface::class)
        ;
    }
}
