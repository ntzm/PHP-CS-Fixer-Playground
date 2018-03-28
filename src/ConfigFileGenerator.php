<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

final class ConfigFileGenerator
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
        return "'$indent'";
    }

    private function formatLineEnding(string $lineEnding): string
    {
        return "'$lineEnding'";
    }

    private function formatRules(array $rules): string
    {
        if (empty($rules)) {
            return '[]';
        }

        $result = '';

        foreach ($rules as $name => $options) {
            $result .= sprintf(
                "        '%s' => %s,\n",
                $name,
                $this->formatOptions($options)
            );
        }

        return sprintf("[\n%s]", $result);
    }

    private function formatOptions($options): string
    {
        if ($options === true) {
            return 'true';
        }

        if ($options === false) {
            return 'false';
        }

        return 'null';
    }
}
