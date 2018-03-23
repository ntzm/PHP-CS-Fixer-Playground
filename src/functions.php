<?php

namespace PhpCsFixerPlayground;

use PhpCsFixer\FixerFactory;

function escape(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function format(string $string): string
{
    return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
}

function view(string $code, array $fixers, string $result): string
{
    $availableFixers = FixerFactory::create()
        ->registerBuiltInFixers()
        ->getFixers()
    ;

    return require __DIR__.'/../templates/index.php';
}
