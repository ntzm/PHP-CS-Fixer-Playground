<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Handler\Api;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Handler\HandlerInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepositoryInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSwitcherInterface;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetFixersHandler implements HandlerInterface
{
    /**
     * @var PhpCsFixerVersionRepositoryInterface
     */
    private $versions;

    /**
     * @var VersionSwitcherInterface
     */
    private $versionSwitcher;

    public function __construct(
        PhpCsFixerVersionRepositoryInterface $versions,
        VersionSwitcherInterface $versionSwitcher
    ) {
        $this->versions = $versions;
        $this->versionSwitcher = $versionSwitcher;
    }

    public function __invoke(array $vars): Response
    {
        $version = $this->versions->get($vars['version']);

        $this->versionSwitcher->switchTo($version);

        $fixers = array_map(function (FixerInterface $fixer): FixerWrapper {
            return new FixerWrapper($fixer);
        }, (new FixerFactory())->registerBuiltInFixers()->getFixers());

        return new JsonResponse($fixers);
    }
}
