<?php

namespace PhpCsFixerPlayground;

use PDO;

final class ConnectionResolver
{
    private const DATABASE = __DIR__.'/../database.sqlite';

    /**
     * @var PDO|null
     */
    private static $instance;

    public function resolve(): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        self::$instance = new PDO(
            sprintf('sqlite:%s', self::DATABASE), null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );

        self::$instance->exec('create table if not exists runs (id integer primary key autoincrement, code text not null, result text not null, rules json not null)');

        return self::$instance;
    }
}
