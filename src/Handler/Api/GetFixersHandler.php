<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler\Api;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetFixersHandler implements HandlerInterface
{
    /** @var FixerFactory */
    private $fixerFactory;

    public function __construct(FixerFactory $fixerFactory)
    {
        $this->fixerFactory = $fixerFactory;
    }

    public function __invoke(array $vars): Response
    {
        $fixers = array_map(function (FixerInterface $fixer): FixerWrapper {
            return new FixerWrapper($fixer);
        }, $this->fixerFactory->registerBuiltInFixers()->getFixers());

        return new JsonResponse($fixers);
    }
}
