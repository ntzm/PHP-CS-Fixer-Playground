<?php

declare(strict_types=1);

use PhpCsFixer\FixerFactory;
use PhpCsFixerPlayground\Fixer;

if ($_SERVER['REQUEST_URI'] !== '/' && strpos($_SERVER['REQUEST_URI'], '/?') !== 0) {
    return false;
}

require __DIR__.'/../vendor/autoload.php';

if (isset($_GET['code']) && is_string($_GET['code'])) {

    $code = $_GET['code'];

    try {
        $fixed = (new Fixer())->fix($code);

        $result = highlight_string($fixed, true);
    } catch (ParseError $e) {
        $result = htmlentities($e->getMessage());
    }
} else {
    $code = "<?php\n\n";
}

$fixers = FixerFactory::create()->registerBuiltInFixers()->getFixers();

require __DIR__.'/../templates/index.php';
