<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PhpCsFixerPlayground\RequestRuleParser;
use PhpCsFixerPlayground\RequestRuleParserInterface;

final class RequestRuleParserServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        RequestRuleParserInterface::class,
    ];

    public function register(): void
    {
        $this->container->add(
            RequestRuleParserInterface::class,
            RequestRuleParser::class
        );
    }
}
