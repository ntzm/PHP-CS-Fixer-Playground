<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Zend\Code\Generator\ValueGenerator;

final class ConfigFileGenerator implements ConfigFileGeneratorInterface
{
    private const TEMPLATE = <<<'EOD'
<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return Config::create()
    ->setRiskyAllowed(true)
    ->setIndent(%s)
    ->setLineEnding(%s)
    ->setRules(%s)
    ->setFinder(
        Finder::create()->in(__DIR__)
    )
;
EOD;

    public function generate(
        array $rules,
        string $indent,
        string $lineEnding
    ): string {
        return sprintf(
            self::TEMPLATE,
            $this->formatIndent($indent),
            $this->formatLineEnding($lineEnding),
            $this->formatRules($rules)
        );
    }

    private function formatIndent(string $indent): string
    {
        return sprintf("'%s'", $indent);
    }

    private function formatLineEnding(string $lineEnding): string
    {
        return sprintf('"%s"', $lineEnding);
    }

    private function formatRules(array $rules): string
    {
        if ($rules === []) {
            return '[]';
        }

        $generator = new ValueGenerator($rules, ValueGenerator::TYPE_ARRAY_SHORT);
        $generator->setIndentation('    ');

        $code = $generator->generate();

        $result = '';

        foreach (explode("\n", $code) as $line) {
            $result .= sprintf("    %s\n", $line);
        }

        return trim($result);
    }
}
