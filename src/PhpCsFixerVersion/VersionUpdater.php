<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use GuzzleHttp\ClientInterface;
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
            $directory = __DIR__.'/../../data/php-cs-fixer-versions/'.$tag['name'];
            $zipPath = $directory.'.zip';

            if (is_dir($directory)) {
                continue;
            }

            $zipResponse = $this->client->request('get', $tag['zipball_url']);
            file_put_contents($zipPath, $zipResponse->getBody()->getContents());

            $zip = new ZipArchive();
            $status = $zip->open($zipPath);

            if ($status !== true) {
                throw new RuntimeException('Could not open zipball: '.$status);
            }

            $zip->extractTo($directory);
            $zip->close();

            unlink($zipPath);
        }
    }
}
