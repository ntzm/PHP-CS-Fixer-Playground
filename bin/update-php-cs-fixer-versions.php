<?php

declare(strict_types=1);

use PhpCsFixerPlayground\Container;
use PhpCsFixerPlayground\Entity\PhpCsFixerVersion;
use PhpCsFixerPlayground\PhpCsFixerVersion\PhpCsFixerVersionRepository;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionRetriever;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionSaver;

require __DIR__.'/../vendor/autoload.php';

$container = new Container();

/** @var VersionRetriever $versionRetriever */
$versionRetriever = $container->get(VersionRetriever::class);

/** @var VersionSaver $versionSaver */
$versionSaver = $container->get(VersionSaver::class);

/** @var PhpCsFixerVersionRepository $versionRepository */
$versionRepository = $container->get(PhpCsFixerVersionRepository::class);

$versions = $versionRetriever->retrieve();

foreach ($versions as $version => $zipUrl) {
    if ($versionRepository->has($version)) {
        echo sprintf('Version %s already exists'.PHP_EOL, $version);

        continue;
    }

    echo sprintf('Saving version %s'.PHP_EOL, $version);

    $versionSaver->save($version, $zipUrl);

    $versionRepository->save(new PhpCsFixerVersion($version));
}
