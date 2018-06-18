<?php

declare(strict_types=1);

namespace PhpCsFixerPlayground\Tests;

use PhpCsFixerPlayground\ParseRulesFromRequest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerPlayground\ParseRulesFromRequest
 */
final class ParseRulesFromRequestTest extends TestCase
{
    /**
     * @param array $expected
     * @param array $input
     *
     * @dataProvider provideTestCases
     */
    public function test(array $expected, array $input): void
    {
        $parser = new ParseRulesFromRequest();

        $this->assertSame($expected, $parser->__invoke($input));
    }

    public function provideTestCases(): array
    {
        return [
            [
                [
                    'rule_1' => true,
                    'rule_3' => true,
                ],
                [
                    'rule_1' => [
                        '_enabled' => '_true',
                    ],
                    'rule_2' => [
                        '_enabled' => '_false',
                    ],
                    'rule_3' => [
                        '_enabled' => '_true',
                    ],
                ],
            ],
            [
                [
                    'rule_1' => [
                        'option_1' => true,
                        'option_2' => false,
                        'option_3' => null,
                        'option_4' => [
                            'foo',
                            'bar',
                        ],
                    ],
                ],
                [
                    'rule_1' => [
                        '_enabled' => '_true',
                        'option_1' => '_true',
                        'option_2' => '_false',
                        'option_3' => '_null',
                        'option_4' => "foo\r\nbar",
                    ],
                ],
            ],
            [
                [
                    'rule_1' => [
                        'option_1' => [
                            'value_1',
                            'value_2',
                        ],
                    ],
                ],
                [
                    'rule_1' => [
                        '_enabled' => '_true',
                        'option_1' => [
                            'value_1',
                            'value_2',
                        ],
                    ],
                ],
            ],
            [
                [
                    'rule_1' => [
                        'option_1' => [
                            'foo' => 1,
                            'bar' => 2,
                            'baz' => 3,
                        ],
                    ],
                ],
                [
                    'rule_1' => [
                        '_enabled' => '_true',
                        'option_1' => [
                            '_keys' => [
                                'foo',
                                'bar',
                                'baz',
                            ],
                            '_values' => [
                                1,
                                2,
                                3,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
