<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use PhpCsFixer\Console\Application;
use PhpCsFixer\FixerFactory;
use SebastianBergmann\Diff\Differ;
use Twig\Environment;

final class ViewFactory implements ViewFactoryInterface
{
    private $twig;

    private $differ;

    private $fixerFactory;

    public function __construct(Environment $twig, Differ $differ, FixerFactory $fixerFactory)
    {
        $this->twig = $twig;
        $this->differ = $differ;
        $this->fixerFactory = $fixerFactory;
    }

    public function make(string $code, array $fixers, string $result): string
    {
        $phpCsFixerVersion = Application::VERSION;

        $availableFixers = $this->fixerFactory
            ->registerBuiltInFixers()
            ->getFixers()
        ;

        $diff = $this->differ->diff($code, $result);

        return $this->twig->render(
            'index.twig',
            compact('code', 'fixers', 'result', 'availableFixers', 'phpCsFixerVersion', 'diff')
        );
    }
}
