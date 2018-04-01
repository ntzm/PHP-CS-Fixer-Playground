<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use GuzzleHttp\ClientInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

final class VersionUpdater
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function update(): void
    {
        $response = $this->client->request('get', 'https://api.github.com/repos/friendsofphp/php-cs-fixer/tags', [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $tags = json_decode($response->getBody()->getContents(), true);

        foreach ($tags as $tag) {
            $dir = __DIR__.'/../../data/php-cs-fixer-versions/'.ltrim($tag['name'], 'v');

            if (is_dir($dir)) {
                continue;
            }

            $zipPath = $dir.'.zip';
            $tempDir = $dir.'_tmp';

            $zipResponse = $this->client->request('get', $tag['zipball_url']);
            file_put_contents($zipPath, $zipResponse->getBody()->getContents());

            $zip = new ZipArchive();
            $zip->open($zipPath);
            $zip->extractTo($tempDir);
            $zip->close();

            // GitHub's archives have a top-level directory with the repo's name
            // and with a seemingly random string. We'll extract it from the
            // directory, where the first two entries will be `.` and `..`
            $tempTop = scandir($tempDir, SCANDIR_SORT_NONE)[2];

            rename($tempDir.'/'.$tempTop.'/src', $dir);

            unlink($zipPath);

            $this->removeDirectory($tempDir);
        }
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
