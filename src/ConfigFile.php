<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Zend\Code\Generator\ValueGenerator;

final class ConfigFile
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @var string
     */
    private $indent;

    /**
     * @var LineEnding
     */
    private $lineEnding;

    public function __construct(
        array $rules,
        string $indent,
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
        if ($this->indent === "\t") {
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

        $code = $generator->generate();

        $result = '';

        foreach (explode("\n", $code) as $line) {
            $result .= sprintf("    %s\n", $line);
        }

        return trim($result);
    }
}
