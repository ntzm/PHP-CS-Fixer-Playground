<?php

namespace PhpCsFixerPlayground;

use PhpCsFixer\Console\Application;
use PhpCsFixer\FixerFactory;
use SebastianBergmann\Diff\Differ;
use Twig_Environment;

final class ViewFactory implements ViewFactoryInterface
{
    private $twig;

    private $differ;

    public function __construct(Twig_Environment $twig, Differ $differ)
    {
        $this->twig = $twig;
        $this->differ = $differ;
    }

    public function make(string $code, array $fixers, string $result): string
    {
        $phpCsFixerVersion = Application::VERSION;

        $availableFixers = FixerFactory::create()
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
