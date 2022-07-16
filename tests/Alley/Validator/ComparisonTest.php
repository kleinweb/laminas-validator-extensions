<?php

/*
 * This file is part of the laminas-validator-extensions package.
 *
 * (c) Alley <info@alley.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Alley\Validator;

use PHPUnit\Framework\TestCase;

final class ComparisonTest extends TestCase
{
    /**
     * @dataProvider dataValidInput
     */
    public function testValidInput($value, $options)
    {
        $validator = new Comparison($options);
        $this->assertTrue($validator->isValid($value));
    }

    public function dataValidInput()
    {
        return [
            '==' => [
                42,
                [
                    'operator' => '==',
                    'compared' => '42',
                ],
            ],
            '===' => [
                42,
                [
                    'operator' => '===',
                    'compared' => 42,
                ],
            ],
            '!=' => [
                43,
                [
                    'operator' => '<>',
                    'compared' => '42',
                ],
            ],
            '!==' => [
                43,
                [
                    'operator' => '!==',
                    'compared' => 42,
                ],
            ],
            '<' => [
                41,
                [
                    'operator' => '<',
                    'compared' => 42,
                ],
            ],
            '>' => [
                43,
                [
                    'operator' => '>',
                    'compared' => 42,
                ],
            ],
            '<=' => [
                42,
                [
                    'operator' => '<=',
                    'compared' => 42,
                ],
            ],
            '>=' => [
                42,
                [
                    'operator' => '>=',
                    'compared' => 42,
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataInvalidInput
     */
    public function testInvalidInput($value, $options, $messages)
    {
        $validator = new Comparison($options);
        $this->assertFalse($validator->isValid($value));
        $this->assertSame($messages, $validator->getMessages());
    }

    public function dataInvalidInput()
    {
        return [
            '==' => [
                43,
                [
                    'operator' => '==',
                    'compared' => '42',
                ],
                ['notEqual' => 'Must be equal to 42 but is 43.'],
            ],
            '===' => [
                '42',
                [
                    'operator' => '===',
                    'compared' => 42,
                ],
                ['notIdentical' => 'Must be identical to 42 but is 42.'],
            ],
            '!=' => [
                42,
                [
                    'operator' => '<>',
                    'compared' => '42',
                ],
                ['isEqual' => 'Must not be equal to 42 but is 42.'],
            ],
            '!==' => [
                42,
                [
                    'operator' => '!==',
                    'compared' => 42,
                ],
                ['isIdentical' => 'Must not be identical to 42.'],
            ],
            '<' => [
                43,
                [
                    'operator' => '<',
                    'compared' => 42,
                ],
                ['notLessThan' => 'Must be less than 42 but is 43.'],
            ],
            '>' => [
                41,
                [
                    'operator' => '>',
                    'compared' => 42,
                ],
                ['notGreaterThan' => 'Must be greater than 42 but is 41.'],
            ],
            '<=' => [
                43,
                [
                    'operator' => '<=',
                    'compared' => 42,
                ],
                ['notLessThanOrEqualTo' => 'Must be less than or equal to 42 but is 43.'],
            ],
            '>=' => [
                41,
                [
                    'operator' => '>=',
                    'compared' => 42,
                ],
                ['notGreaterThanOrEqualTo' => 'Must be greater than or equal to 42 but is 41.'],
            ],
        ];
    }
}
