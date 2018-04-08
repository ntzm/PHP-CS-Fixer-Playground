<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler\Api;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepositoryInterface;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetFixersHandler implements HandlerInterface
{
    /**
     * @var FixerFactory
     */
    private $fixerFactory;

    /**
     * @var PhpCsFixerVersionRepositoryInterface
     */
    private $versions;

    public function __construct(
        FixerFactory $fixerFactory,
        PhpCsFixerVersionRepositoryInterface $versions
    ) {
        $this->fixerFactory = $fixerFactory;
        $this->versions = $versions;
    }

    public function __invoke(array $vars): Response
    {
        $version = $this->versions->get($vars['version']);

        $fixers = array_map(function (FixerInterface $fixer): FixerWrapper {
            return new FixerWrapper($fixer);
        }, $this->fixerFactory->registerBuiltInFixers()->getFixers());

        return new JsonResponse($fixers);
    }
}
