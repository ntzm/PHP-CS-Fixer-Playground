<?php

declare(strict_types=1);

use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionUpdater;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();

/** @var VersionUpdater $versionUpdater */
$versionUpdater = $container->get(VersionUpdater::class);

$versionUpdater->update();
