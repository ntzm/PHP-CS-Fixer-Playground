<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

final class Version20181014082418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the php_cs_fixer_versions table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('php_cs_fixer_versions');

        $table->addColumn('number', Type::STRING, [
            'length' => 10,
        ]);

        $table->addColumn('name', Type::STRING, [
            'length' => 50,
        ]);

        $table->setPrimaryKey(['number']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('php_cs_fixer_versions');
    }
}
