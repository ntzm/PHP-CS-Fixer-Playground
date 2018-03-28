<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\ConfigFileGenerator;
use PHPUnit\Framework\TestCase;

final class ConfigFileGeneratorTest extends TestCase
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

        $generator = new ConfigFileGenerator();

        $this->assertSame($expected, $generator->generate(['foo' => true, 'bar' => ['baz' => 'bop']], '    ', '\n'));
    }
}
