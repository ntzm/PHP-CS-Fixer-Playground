<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixer\Tests\TestCase;
use PhpCsFixerPlayground\Run;

/**
 * @covers \PhpCsFixerPlayground\Run
 */
final class RunTest extends TestCase
{
    public function testGetCode(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n');

        $this->assertSame('<?php echo "hi";', $run->getCode());
    }

    public function testGetRules(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote'], '    ', '\n');

        $this->assertSame(['single_quote'], $run->getRules());
    }

    public function testGetIndent(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n');

        $this->assertSame('    ', $run->getIndent());
    }

    public function testGetLineEnding(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n');

        $this->assertSame('\n', $run->getLineEnding());
    }

    public function testGetRealLineEndingRN(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\r\n');

        $this->assertSame("\r\n", $run->getRealLineEnding());
    }

    public function testGetRealLineEndingN(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n');

        $this->assertSame("\n", $run->getRealLineEnding());
    }

    public function testGetHashUnsaved(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n');

        $this->assertNull($run->getHash());
    }

    public function testGetHashSaved(): void
    {
        $run = new Run('<?php echo "hi";', [], '    ', '\n', 'foobar');

        $this->assertSame('foobar', $run->getHash());
    }
}
