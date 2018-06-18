<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\HttpFoundation\Request;

final class RequestServiceProvider extends AbstractServiceProvider
{
    /** @var string[] */
    protected $provides = [
        Request::class,
    ];

    public function register(): void
    {
        $this->container
            ->add(Request::class, function (): Request {
                return Request::createFromGlobals();
            });
    }
}
