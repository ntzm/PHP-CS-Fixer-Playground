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

        if (!file_exists(self::DATABASE)) {
            touch(self::DATABASE);
        }

        self::$instance = new PDO(
            sprintf('sqlite:%s', self::DATABASE)
        );

        self::$instance->exec('create table if not exists runs (id varchar(16) primary key, code text not null, result text not null, rules json not null)');

        die(var_dump(self::$instance->errorInfo()));

        return self::$instance;
    }
}
