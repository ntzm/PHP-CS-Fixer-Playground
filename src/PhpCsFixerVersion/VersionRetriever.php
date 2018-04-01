<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\PhpCsFixerVersion;

use GuzzleHttp\ClientInterface;

final class VersionRetriever
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function retrieve(): array
    {
        $response = $this->client->request('get', 'https://api.github.com/repos/friendsofphp/php-cs-fixer/tags', [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $result = [];

        $tags = json_decode($response->getBody()->getContents(), true);

        foreach ($tags as $tag) {
            $result[ltrim($tag['name'], 'v')] = $tag['zipball_url'];
        }

        return $result;
    }
}
