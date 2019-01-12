<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LogServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        LoggerInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(LoggerInterface::class, $this->getLogger());
    }

    private function getLogger(): Logger
    {
        $logger = new Logger('app');
        $logger->pushHandler(new ErrorLogHandler());

        return $logger;
    }
}
