<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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

    public function formatFilter(string $string): string
    {
        return preg_replace('/`(.+?)`/', '<code>$1</code>', $string);
    }

    public function linkRulesFilter(array $rules): string
    {
        return implode(', ', array_map(function (string $rule): string {
            return "<a href=\"#{$rule}\"><code>{$rule}</code></a>";
        }, $rules));
    }
}
