<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Fixer;

use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixerPlayground\Fixer\FixReport;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Fixer\FixReport
 */
final class FixReportTest extends TestCase
{
    public function testGetResult(): void
    {
        $report = new FixReport('<?php echo "hi";', []);

        $this->assertSame('<?php echo "hi";', $report->getResult());
    }

    public function testGetAppliedFixers(): void
    {
        $fixers = [new NoAliasFunctionsFixer()];

        $report = new FixReport('<?php echo "hi";', $fixers);

        $this->assertEquals($fixers, $report->getAppliedFixers());
    }
}
