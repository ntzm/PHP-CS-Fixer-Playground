<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Entity;

use PhpCsFixer\Tests\TestCase;
use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\LineEnding;

/**
 * @covers \PhpCsFixerPlayground\Entity\Run
 */
final class RunTest extends TestCase
{
    public function testGetCode(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', LineEnding::fromVisible('\n'));

        $this->assertSame('<?php echo "hi";', $run->getCode());
    }

    public function testGetRules(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote'], '    ', LineEnding::fromVisible('\n'));

        $this->assertSame(['single_quote'], $run->getRules());
    }

    public function testGetIndent(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', LineEnding::fromVisible('\n'));

        $this->assertSame('    ', $run->getIndent());
    }

    public function testGetLineEnding(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', LineEnding::fromVisible('\n'));

        $this->assertSame('\n', $run->getLineEnding()->getVisible());
        $this->assertSame("\n", $run->getLineEnding()->getReal());
    }
}
