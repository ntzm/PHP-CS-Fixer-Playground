<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Fix;

use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixerPlayground\Fix\FixReport;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Fix\FixReport
 */
final class FixReportTest extends TestCase
{
    public function testGetResult(): void
    {
        $report = new FixReport('<?php echo "hi";', [], []);

        $this->assertSame('<?php echo "hi";', $report->getResult());
    }

    public function testGetAppliedFixers(): void
    {
        $fixers = [new NoAliasFunctionsFixer()];

        $report = new FixReport('<?php echo "hi";', $fixers, []);

        $this->assertEquals($fixers, $report->getAppliedFixers());
    }

    public function testGetDeprecationMessages(): void
    {
        $deprecationMessages = ['foo', 'bar'];

        $report = new FixReport('<?php echo "hi";', [], $deprecationMessages);

        $this->assertSame($deprecationMessages, $report->getDeprecationMessages());
    }
}
