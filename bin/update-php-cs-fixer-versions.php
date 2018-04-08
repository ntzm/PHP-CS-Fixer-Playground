<?php

declare(strict_types=1);

use League\Container\Container;
use League\Container\ReflectionContainer;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepositoryInterface;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionRetriever;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSaver;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();
$container->delegate(new ReflectionContainer());

/** @var VersionRetriever $versionRetriever */
$versionRetriever = $container->get(VersionRetriever::class);

/** @var VersionSaver $versionSaver */
$versionSaver = $container->get(VersionSaver::class);

/** @var PhpCsFixerVersionRepositoryInterface $versionRepository */
$versionRepository = $container->get(PhpCsFixerVersionRepositoryInterface::class);

$versions = $versionRetriever->retrieve();

foreach ($versions as $version => $zipUrl) {
    if (strpos($version, '2.') !== 0) {
        echo sprintf('Version %s is not supported'.PHP_EOL, $version);

        continue;
    }

    if ($versionRepository->has($version)) {
        echo sprintf('Version %s already exists'.PHP_EOL, $version);

        continue;
    }

    echo sprintf('Saving version %s'.PHP_EOL, $version);

    $versionSaver->save($version, $zipUrl);

    $versionRepository->save(new PhpCsFixerVersion($version));
}
