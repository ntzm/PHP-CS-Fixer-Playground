<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use Hashids\HashidsInterface;
use Mockery;
use PDO;
use PDOStatement;
use PhpCsFixerPlayground\Run;
use PhpCsFixerPlayground\RunNotFoundException;
use PhpCsFixerPlayground\RunRepository;
use PHPUnit\Framework\TestCase;

final class RunRepositoryTest extends TestCase
{
    public function testGetByHash(): void
    {
        $statement = Mockery::mock(PDOStatement::class);
        $statement->expects('execute')->once();
        $statement->expects('fetch')->andReturn([
            'id' => 5,
            'code' => '<?php echo "hi";',
            'rules' => '{"single_quote": true}',
        ]);

        $pdo = Mockery::mock(PDO::class);
        $pdo->expects('prepare')->once()->andReturn($statement);

        $hashids = Mockery::mock(HashidsInterface::class);
        $hashids->expects('decode')->withArgs(['foo'])->andReturn([5]);

        $runs = new RunRepository($pdo, $hashids);

        $run = $runs->getByHash('foo');

        $this->assertSame('foo', $run->getHash());
        $this->assertSame('<?php echo "hi";', $run->getCode());
        $this->assertSame(['single_quote' => true], $run->getRules());
    }

    public function testGetByHashNonExistent(): void
    {
        $statement = Mockery::mock(PDOStatement::class);
        $statement->expects('execute')->once();
        $statement->expects('fetch')->andReturn(false);

        $pdo = Mockery::mock(PDO::class);
        $pdo->expects('prepare')->once()->andReturn($statement);

        $hashids = Mockery::mock(HashidsInterface::class);
        $hashids->expects('decode')->withArgs(['foo'])->andReturn([5]);

        $runs = new RunRepository($pdo, $hashids);

        $this->expectException(RunNotFoundException::class);
        $this->expectExceptionMessage('Cannot find run with hash foo');

        $runs->getByHash('foo');
    }

    public function testSave(): void
    {
        $statement = Mockery::mock(PDOStatement::class);
        $statement->expects('bindValue')->twice();
        $statement->expects('execute')->once();

        $pdo = Mockery::mock(PDO::class);
        $pdo->expects('prepare')->once()->andReturn($statement);
        $pdo->expects('lastInsertId')->andReturn('5');

        $hashids = Mockery::mock(HashidsInterface::class);
        $hashids->expects('encode')->withArgs(['5'])->andReturn('foo');

        $runs = new RunRepository($pdo, $hashids);

        $inputRun = new Run('<?php echo "hi";', ['single_quote' => true]);

        $outputRun = $runs->save($inputRun);

        $this->assertNotSame($inputRun, $outputRun);

        $this->assertSame('<?php echo "hi";', $outputRun->getCode());
        $this->assertSame(['single_quote' => true], $outputRun->getRules());
        $this->assertSame('foo', $outputRun->getHash());
    }
}
