<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\ParseRulesFromRequest;
use PhpCsFixerPlayground\ParseRulesFromRequestInterface;

final class ParseRulesFromRequestServiceProvider extends AbstractServiceProvider
{
    /** @var Container */
    protected $container;

    /** @var string[] */
    protected $provides = [
        ParseRulesFromRequestInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            ParseRulesFromRequestInterface::class,
            ParseRulesFromRequest::class
        );
    }
}
