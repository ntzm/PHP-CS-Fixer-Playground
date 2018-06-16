<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

final class Version20180616072518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the runs table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('runs');

        $table->addColumn('id', Type::GUID);

        $table->addColumn('code', Type::TEXT);

        $table->addColumn('rules', Type::JSON);

        $table->addColumn('indent', Type::STRING, [
            'length' => 4,
        ]);

        $table->addColumn('line_ending', Type::STRING, [
            'length' => 2,
        ]);

        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('runs');
    }
}
