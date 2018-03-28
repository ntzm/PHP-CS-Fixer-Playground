<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

final class TwigExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'format',
                [$this, 'formatFilter'],
                ['pre_escape' => 'html', 'is_safe' => ['html']]
            ),
            new TwigFilter(
                'link_rules',
                [$this, 'linkRulesFilter'],
                ['pre_escape' => 'html', 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @return TwigTest[]
     */
    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [$this, 'instanceofTest']),
        ];
    }

    public function instanceofTest(object $instance, string $class): bool
    {
        return $instance instanceof $class;
    }

    public function formatFilter(string $string): string
    {
        return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
    }

    public function linkRulesFilter(array $rules): string
    {
        return implode(', ', array_map(function (string $rule): string {
            return sprintf('<a href="#%s"><code>%s</code></a>', $rule, $rule);
        }, $rules));
    }
}
