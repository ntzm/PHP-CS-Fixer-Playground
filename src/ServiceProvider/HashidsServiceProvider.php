<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class HashidsServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        HashidsInterface::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(HashidsInterface::class, function (): Hashids {
                return new Hashids(getenv('HASHIDS_SECRET') ?: '', 10);
            });
    }
}
