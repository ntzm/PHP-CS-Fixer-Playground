<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Version\Version;
use PhpCsFixerPlayground\Migration\Version20180616072518;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Migration\Version20180616072518
 */
final class Version20180616072518Test extends TestCase
{
    public function testGetDescription(): void
    {
        /** @var Version|MockObject $version */
        $version = $this->createMock(Version::class);

        $migration = new Version20180616072518($version);

        $this->assertSame('Create the runs table', $migration->getDescription());
    }

    public function testUp(): void
    {
        $table = $this->createMock(Table::class);
        $table
            ->method('addColumn')
            ->withConsecutive(
                ['id', Type::GUID],
                ['code', Type::TEXT],
                ['rules', Type::JSON],
                ['indent', Type::STRING, ['length' => 4]],
                ['line_ending', Type::STRING, ['length' => 2]]
            )
        ;
        $table
            ->expects($this->once())
            ->method('setPrimaryKey')
            ->with(['id'])
        ;

        /** @var Schema|MockObject $schema */
        $schema = $this->createMock(Schema::class);
        $schema
            ->expects($this->once())
            ->method('createTable')
            ->with('runs')
            ->willReturn($table)
        ;

        /** @var Version|MockObject $version */
        $version = $this->createMock(Version::class);

        $migration = new Version20180616072518($version);

        $migration->up($schema);
    }

    public function testDown(): void
    {
        /** @var Schema|MockObject $schema */
        $schema = $this->createMock(Schema::class);
        $schema
            ->expects($this->once())
            ->method('dropTable')
            ->with('runs')
        ;

        /** @var Version|MockObject $version */
        $version = $this->createMock(Version::class);

        $migration = new Version20180616072518($version);

        $migration->down($schema);
    }
}
