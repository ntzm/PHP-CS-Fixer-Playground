<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use GuzzleHttp\ClientInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

final class VersionSaver
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function save(string $version, string $zipUrl): void
    {
        $dir = __DIR__.'/../../data/php-cs-fixer-versions/'.$version;

        $tempDir = $dir.'_tmp';

        $this->unzipRemote($zipUrl, $tempDir);

        // GitHub's archives have a top-level directory with the repo's name
        // and with a seemingly random string. We'll extract it from the
        // directory, where the first two entries will be `.` and `..`
        $filesInTempDir = scandir($tempDir, SCANDIR_SORT_NONE);

        if ($filesInTempDir === false) {
            throw new RuntimeException(
                sprintf('Could not read directory %s', $tempDir)
            );
        }

        $tempTop = $filesInTempDir[2];

        rename($tempDir.'/'.$tempTop.'/src', $dir);

        $this->removeDirectory($tempDir);
    }

    private function unzipRemote(string $url, string $destination): void
    {
        $zipResponse = $this->client->request('get', $url);
        $zipPath = $destination.'.zip';

        file_put_contents($zipPath, $zipResponse->getBody()->getContents());

        $zip = new ZipArchive();
        $zip->open($zipPath);
        $zip->extractTo($destination);
        $zip->close();

        unlink($zipPath);
    }

    private function removeDirectory(string $dir): void
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileOrDir) {
            if ($fileOrDir->isDir()) {
                rmdir($fileOrDir->getRealPath());
            } else {
                unlink($fileOrDir->getRealPath());
            }
        }

        rmdir($dir);
    }
}
