<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\ConfigFile;
use PhpCsFixerPlayground\Indent;
use PhpCsFixerPlayground\LineEnding;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ConfigFile
 */
final class ConfigFileTest extends TestCase
{
    public function testGenerate(): void
    {
        $expected = <<<'EOD'
        <?php

        use PhpCsFixer\Config;
        use PhpCsFixer\Finder;

        return Config::create()
            ->setRiskyAllowed(true)
            ->setIndent('    ')
            ->setLineEnding("\n")
            ->setRules([
                'foo' => true,
                'bar' => [
                    'baz' => 'bop',
                ],
            ])
            ->setFinder(
                Finder::create()->in(__DIR__)
            )
        ;
        EOD;

        $generator = new ConfigFile(
            ['foo' => true, 'bar' => ['baz' => 'bop']],
            new Indent('    '),
            LineEnding::fromVisible('\n'),
        );

        $this->assertSame($expected, $generator->__toString());
    }

    public function testGenerateWithTabIndent(): void
    {
        $expected = <<<'EOD'
        <?php

        use PhpCsFixer\Config;
        use PhpCsFixer\Finder;

        return Config::create()
            ->setRiskyAllowed(true)
            ->setIndent("\t")
            ->setLineEnding("\n")
            ->setRules([
                'foo' => true,
                'bar' => [
                    'baz' => 'bop',
                ],
            ])
            ->setFinder(
                Finder::create()->in(__DIR__)
            )
        ;
        EOD;

        $generator = new ConfigFile(
            ['foo' => true, 'bar' => ['baz' => 'bop']],
            new Indent("\t"),
            LineEnding::fromVisible('\n'),
        );

        $this->assertSame($expected, $generator->__toString());
    }

    public function testGenerateWithNoRules(): void
    {
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

        $generator = new ConfigFile(
            [],
            new Indent('    '),
            LineEnding::fromVisible('\n'),
        );

        $this->assertSame($expected, $generator->__toString());
    }
}
