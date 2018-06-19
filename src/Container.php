<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use League\Container\Container as LeagueContainer;
use League\Container\ReflectionContainer;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class Container
{
    /** @var LeagueContainer */
    private $container;

    public function __construct()
    {
        $this->container = (new LeagueContainer())->delegate(new ReflectionContainer());

        $this->registerServiceProviders();
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    private function registerServiceProviders(): void
    {
        /** @var SplFileInfo $file */
        foreach ((new Finder())->in(__DIR__.'/ServiceProvider') as $file) {
            $this->container->addServiceProvider(
                "PhpCsFixerPlayground\\ServiceProvider\\{$file->getBasename('.php')}"
            );
        }
    }
}
