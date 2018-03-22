<?php

namespace PhpCsFixerPlayground;

function escape(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function format(string $string): string
{
    return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
}
