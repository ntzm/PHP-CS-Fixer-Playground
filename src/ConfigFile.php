<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Zend\Code\Generator\ValueGenerator;

final class ConfigFile
{
    /** @var array */
    private $rules;

    /** @var Indent */
    private $indent;

    /** @var LineEnding */
    private $lineEnding;

    public function __construct(
        array $rules,
        Indent $indent,
        LineEnding $lineEnding
    ) {
        $this->rules = $rules;
        $this->indent = $indent;
        $this->lineEnding = $lineEnding;
    }

    public function __toString(): string
    {
        return <<<CONFIG
<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return Config::create()
    ->setRiskyAllowed(true)
    ->setIndent({$this->getIndent()})
    ->setLineEnding({$this->getLineEnding()})
    ->setRules({$this->getRules()})
    ->setFinder(
        Finder::create()->in(__DIR__)
    )
;
CONFIG;
    }

    private function getIndent(): string
    {
        if ((string) $this->indent === "\t") {
            return '"\t"';
        }

        return "'{$this->indent}'";
    }

    private function getLineEnding(): string
    {
        return '"'.$this->lineEnding->getVisible().'"';
    }

    private function getRules(): string
    {
        if ($this->rules === []) {
            return '[]';
        }

        $generator = new ValueGenerator(
            $this->rules,
            ValueGenerator::TYPE_ARRAY_SHORT
        );

        $generator->setIndentation('    ');

        return $this->indentLines($generator->generate());
    }

    private function indentLines(string $lines): string
    {
        return trim(
            implode("\n",
                array_map(
                    function (string $line): string {
                        return "    $line";
                    },
                    explode("\n", $lines)
                )
            )
        );
    }
}
