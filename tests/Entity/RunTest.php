<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\Entity;

use PhpCsFixerPlayground\Entity\Run;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\Entity\Run
 */
final class RunTest extends TestCase
{
    public function testGetId(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $this->assertSame(4, $run->getId()->getVersion());
    }

    public function testGetCode(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $this->assertSame('<?php echo "hi";', $run->getCode());
    }

    public function testGetRules(): void
    {
        $run = new Run(
            '<?php echo "hi";', ['single_quote'],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $this->assertSame(['single_quote'], $run->getRules());
    }

    public function testGetIndent(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $this->assertSame('    ', (string) $run->getIndent());
    }

    public function testGetLineEnding(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $this->assertSame('\n', $run->getLineEnding()->getVisible());
        $this->assertSame("\n", $run->getLineEnding()->getReal());
    }

    public function testGetConfigFile(): void
    {
        $run = new Run(
            '<?php echo "hi";',
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n')
        );

        $expected = <<<'EOD'
<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return Config::create()
    ->setRiskyAllowed(true)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setRules([])
    ->setFinder(
        Finder::create()->in(__DIR__)
    )
;
EOD;

        $this->assertSame($expected, (string) $run->getConfigFile());
    }
}
