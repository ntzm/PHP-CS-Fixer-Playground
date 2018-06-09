<?php

declare(strict_types=1);

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepositoryInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionRetrieverInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSaverInterface;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();
$container->delegate(new ReflectionContainer());

/** @var VersionRetrieverInterface $versionRetriever */
$versionRetriever = $container->get(VersionRetrieverInterface::class);

/** @var VersionSaverInterface $versionSaver */
$versionSaver = $container->get(VersionSaverInterface::class);

/** @var PhpCsFixerVersionRepositoryInterface $versionRepository */
$versionRepository = $container->get(PhpCsFixerVersionRepositoryInterface::class);

$versions = $versionRetriever->retrieve();

foreach ($versions as $version => $zipUrl) {
    if (strpos($version, '2.') !== 0) {
        printf('Version %s is not supported'.PHP_EOL, $version);

        continue;
    }

    if ($versionRepository->has($version)) {
        printf('Version %s already exists'.PHP_EOL, $version);

        continue;
    }

    printf('Saving version %s'.PHP_EOL, $version);

    $versionSaver->save($version, $zipUrl);

    $versionRepository->save(new PhpCsFixerVersion($version));
}
