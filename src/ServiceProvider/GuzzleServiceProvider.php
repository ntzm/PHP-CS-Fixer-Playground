<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

final class GuzzleServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ClientInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(ClientInterface::class, Client::class);
    }
}
