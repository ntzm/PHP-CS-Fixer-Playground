<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\PhpCsFixerVersion;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PhpCsFixerPlayground\PhpCsFixerVersion\VersionRetriever;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\PhpCsFixerVersion\VersionRetriever
 */
final class VersionRetrieverTest extends TestCase
{
    public function testRetrieve(): void
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(
                200,
                [],
                json_encode([
                    [
                        'name' => 'v2.11.1',
                        'zipball_url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/v2.11.1',
                        'tarball_url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/tarball/v2.11.1',
                        'commit' => [
                            'sha' => 'ad94441c17b8ef096e517acccdbf3238af8a2da8',
                            'url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/commits/ad94441c17b8ef096e517acccdbf3238af8a2da8',
                        ],
                    ],
                    [
                        'name' => 'v2.11.0',
                        'zipball_url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/v2.11.0',
                        'tarball_url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/tarball/v2.11.0',
                        'commit' => [
                            'sha' => '2ac8defbe07599b79005cca764bfffe7aeac0bf2',
                            'url' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/commits/2ac8defbe07599b79005cca764bfffe7aeac0bf2',
                        ],
                    ],
                ])
            ),
        ]));

        $client = new Client(['handler' => $handler]);

        $retriever = new VersionRetriever($client);

        $expected = [
            '2.11.1' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/v2.11.1',
            '2.11.0' => 'https://api.github.com/repos/FriendsOfPHP/PHP-CS-Fixer/zipball/v2.11.0',
        ];

        $this->assertSame($expected, $retriever->retrieve());
    }
}
