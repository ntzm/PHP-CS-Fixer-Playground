<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests\View;

use PhpCsFixerPlayground\View\TwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

/**
 * @covers \PhpCsFixerPlayground\View\TwigExtension
 */
final class TwigExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $extension = new TwigExtension();

        $this->assertContainsOnlyInstancesOf(TwigFilter::class, $extension->getFilters());
    }

    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider provideFormatFilterCases
     */
    public function testFormatFilter(string $expected, string $input): void
    {
        $extension = new TwigExtension();

        $this->assertSame($expected, $extension->formatFilter($input));
    }

    public function provideFormatFilterCases(): array
    {
        return [
            [
                'foo',
                'foo',
            ],
            [
                '<code>foo</code>',
                '`foo`',
            ],
            [
                '<script>alert(1);</script><code>foo</code>',
                '<script>alert(1);</script>`foo`',
            ],
            [
                '``',
                '``',
            ],
        ];
    }

    /**
     * @param string   $expected
     * @param string[] $rules
     *
     * @dataProvider provideLinkRulesFilterCases
     */
    public function testLinkRulesFilter(string $expected, array $rules): void
    {
        $extension = new TwigExtension();

        $this->assertSame($expected, $extension->linkRulesFilter($rules));
    }

    public function provideLinkRulesFilterCases(): array
    {
        return [
            [
                '',
                [],
            ],
            [
                '<a href="#foo"><code>foo</code></a>',
                ['foo'],
            ],
            [
                '<a href="#foo"><code>foo</code></a>, <a href="#bar"><code>bar</code></a>, <a href="#baz"><code>baz</code></a>',
                ['foo', 'bar', 'baz'],
            ],
        ];
    }
}
