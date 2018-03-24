<?php

namespace PhpCsFixerPlayground;

use PhpCsFixer\Console\Application;
use PhpCsFixer\FixerFactory;
use Twig_Environment;
use Twig_Filter;
use Twig_Loader_Filesystem;
use Twig_Test;

function view(string $code, array $fixers, string $result): string
{
    $loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');
    $twig = new Twig_Environment($loader);

    $twig->addTest(new Twig_Test('instanceof', function (object $instance, string $class): bool {
        return $instance instanceof $class;
    }));

    $twig->addFilter(new Twig_Filter('format', function (string $string): string {
        return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
    }, ['pre_escape' => 'html', 'is_safe' => ['html']]));

    $phpCsFixerVersion = Application::VERSION;

    $availableFixers = FixerFactory::create()
        ->registerBuiltInFixers()
        ->getFixers()
    ;

    return $twig->render(
        'index.twig',
        compact('code', 'fixers', 'result', 'availableFixers', 'phpCsFixerVersion')
    );
}
