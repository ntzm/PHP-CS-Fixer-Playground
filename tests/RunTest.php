<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixer\Tests\TestCase;
use PhpCsFixerPlayground\Run;

final class RunTest extends TestCase
{
    public function testGetCode(): void
    {
        $run = new Run('<?php echo "hi";', []);

        $this->assertSame('<?php echo "hi";', $run->getCode());
    }

    public function testGetRules(): void
    {
        $run = new Run('<?php echo "hi";', ['single_quote']);

        $this->assertSame(['single_quote'], $run->getRules());
    }

    public function testGetHashUnsaved(): void
    {
        $run = new Run('<?php echo "hi";', []);

        $this->assertNull($run->getHash());
    }

    public function testGetHashSaved(): void
    {
        $run = new Run('<?php echo "hi";', [], 'foobar');

        $this->assertSame('foobar', $run->getHash());
    }
}
