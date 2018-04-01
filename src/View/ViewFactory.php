<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use PackageVersions\Versions;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Wrapper\FixerWrapper;
use SebastianBergmann\Diff\Differ;
use Twig\Environment;

final class ViewFactory implements ViewFactoryInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Differ
     */
    private $differ;

    /**
     * @var FixerFactory
     */
    private $fixerFactory;

    public function __construct(
        Environment $twig,
        Differ $differ,
        FixerFactory $fixerFactory
    ) {
        $this->twig = $twig;
        $this->differ = $differ;
        $this->fixerFactory = $fixerFactory;
    }

    public function make(
        Run $run,
        string $result,
        array $appliedFixers,
        string $generatedConfig
    ): string {
        $availableFixers = $this->fixerFactory
            ->registerBuiltInFixers()
            ->getFixers()
        ;

        $availableFixers = array_map(function (FixerInterface $fixer): FixerWrapper {
            return new FixerWrapper($fixer);
        }, $availableFixers);

        preg_match(
            '/^v([0-9.]+)@/',
            Versions::getVersion('friendsofphp/php-cs-fixer'),
            $matches
        );
        $phpCsFixerVersion = $matches[1];

        return $this->twig->render(
            'index.twig',
            [
                'code' => $run->getCode(),
                'fixers' => $run->getRules(),
                'indent' => $run->getIndent(),
                'lineEnding' => $run->getLineEnding(),
                'result' => $result,
                'appliedFixers' => $appliedFixers,
                'generatedConfig' => $generatedConfig,
                'availableFixers' => $availableFixers,
                'phpCsFixerVersion' => $phpCsFixerVersion,
                'diff' => $this->differ->diff($run->getCode(), $result),
            ]
        );
    }
}
