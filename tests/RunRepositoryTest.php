<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use Hashids\HashidsInterface;
use PDO;
use PDOStatement;
use PhpCsFixerPlayground\Run;
use PhpCsFixerPlayground\RunNotFoundException;
use PhpCsFixerPlayground\RunRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\RunRepository
 */
final class RunRepositoryTest extends TestCase
{
    public function testGetByHash(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('fetch')->willReturn([
            'id' => 5,
            'code' => '<?php echo "hi";',
            'indent' => '    ',
            'line_ending' => "\n",
            'rules' => '{"single_quote": true}',
        ]);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($statement);

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids->method('decode')->with('foo')->willReturn([5]);

        $runs = new RunRepository($pdo, $hashids);

        $run = $runs->getByHash('foo');

        $this->assertSame('foo', $run->getHash());
        $this->assertSame('<?php echo "hi";', $run->getCode());
        $this->assertSame(['single_quote' => true], $run->getRules());
        $this->assertSame('    ', $run->getIndent());
        $this->assertSame("\n", $run->getLineEnding());
    }

    public function testGetByHashNonExistent(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('fetch')->willReturn(false);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($statement);

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids->method('decode')->with('foo')->willReturn([5]);

        $runs = new RunRepository($pdo, $hashids);

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with hash foo');

        $runs->getByHash('foo');
    }

    public function testSave(): void
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('bindValue');
        $statement->method('execute');

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($statement);
        $pdo->method('lastInsertId')->willReturn('5');

        $hashids = $this->createMock(HashidsInterface::class);
        $hashids->method('encode')->with('5')->willReturn('foo');

        $runs = new RunRepository($pdo, $hashids);

        $inputRun = new Run('<?php echo "hi";', ['single_quote' => true], '    ', "\n");

        $outputRun = $runs->save($inputRun);

        $this->assertNotSame($inputRun, $outputRun);

        $this->assertSame('<?php echo "hi";', $outputRun->getCode());
        $this->assertSame(['single_quote' => true], $outputRun->getRules());
        $this->assertSame('foo', $outputRun->getHash());
        $this->assertSame('    ', $outputRun->getIndent());
        $this->assertSame("\n", $outputRun->getLineEnding());
    }
}
