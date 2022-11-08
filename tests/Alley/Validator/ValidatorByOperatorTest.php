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

final class ValidatorByOperatorTest extends TestCase
{
    /**
     * @dataProvider dataValidInput
     */
    public function testValidInput(string $operator, $compared, $value)
    {
        $validator = new ValidatorByOperator($operator, $compared);
        $this->assertTrue($validator->isValid($value));
    }

    public function dataValidInput()
    {
        return [
            ['REGEX', '/^foo$/', 'foo'],
            ['NOT REGEX', '/^foo$/', 'foo bar'],
            ['IN', ['foo', 'bar'], 'foo'],
            ['NOT IN', ['foo', 'bar'], 'baz'],
            ['CONTAINS', 'foo', 'foobar'],
            ['NOT CONTAINS', 'foo', 'Foobar'],
            ['LIKE', 'foo', 'Foobar'],
            ['NOT LIKE', 'foo', 'barbaz'],
            ['===', 42, 42],
        ];
    }

    /**
     * @dataProvider dataInvalidInput
     */
    public function testInvalidInput(string $operator, $compared, $value)
    {
        $validator = new ValidatorByOperator($operator, $compared);
        $this->assertFalse($validator->isValid($value));
    }

    public function dataInvalidInput()
    {
        return [
            ['REGEX', '/^foo$/', 'foo bar'],
            ['NOT REGEX', '/^foo$/', 'foo'],
            ['IN', ['foo', 'bar'], 'baz'],
            ['NOT IN', ['foo', 'bar'], 'foo'],
            ['CONTAINS', 'foo', 'barbaz'],
            ['NOT CONTAINS', 'foo', 'foobar'],
            ['LIKE', 'foo', 'barbaz'],
            ['NOT LIKE', 'foo', 'Foobar'],
            ['===', 42, 43],
        ];
    }
}
