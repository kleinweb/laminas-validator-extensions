<?php

/*
 * This file is part of the laminas-validator-extensions package.
 *
 * (c) Alley <info@alley.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Alley\Validator;

use PHPUnit\Framework\TestCase;

final class TypeTest extends TestCase
{
    /**
     * @dataProvider dataValidInput
     */
    public function testValidInput($value, $options)
    {
        $validator = new Type($options);
        $this->assertTrue($validator->isValid($value));
    }

    public function dataValidInput()
    {
        return [
            'array' => [
                [],
                ['type' => 'array'],
            ],
            'bool' => [
                true,
                ['type' => 'bool'],
            ],
            'float' => [
                4.2,
                ['type' => 'float'],
            ],
            'int' => [
                42,
                ['type' => 'int'],
            ],
            'null' => [
                null,
                ['type' => 'null'],
            ],
            'numeric' => [
                '42',
                ['type' => 'numeric'],
            ],
            'object' => [
                new \stdClass(),
                ['type' => 'object'],
            ],
            'resource' => [
                \STDOUT,
                ['type' => 'resource'],
            ],
            'string' => [
                '42',
                ['type' => 'string'],
            ],
            'scalar' => [
                'foo',
                ['type' => 'scalar'],
            ],
            'callable' => [
                'intval',
                ['type' => 'callable'],
            ],
            'iterable' => [
                [],
                ['type' => 'iterable'],
            ],
        ];
    }

    /**
     * @dataProvider dataInvalidInput
     */
    public function testInvalidInput($value, $options, $messages)
    {
        $validator = new Type($options);
        $this->assertFalse($validator->isValid($value));
        $this->assertSame($messages, $validator->getMessages());
    }

    public function dataInvalidInput()
    {
        return [
            'array' => [
                42,
                ['type' => 'array'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'array' but 42 is not."],
            ],
            'bool' => [
                42,
                ['type' => 'bool'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'bool' but 42 is not."],
            ],
            'float' => [
                42,
                ['type' => 'float'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'float' but 42 is not."],
            ],
            'int' => [
                '42',
                ['type' => 'int'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'int' but 42 is not."],
            ],
            'null' => [
                42,
                ['type' => 'null'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'null' but 42 is not."],
            ],
            'numeric' => [
                'forty-two',
                ['type' => 'numeric'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'numeric' but forty-two is not."],
            ],
            'object' => [
                42,
                ['type' => 'object'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'object' but 42 is not."],
            ],
            'resource' => [
                42,
                ['type' => 'resource'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'resource' but 42 is not."],
            ],
            'string' => [
                42,
                ['type' => 'string'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'string' but 42 is not."],
            ],
            'scalar' => [
                fn () => null,
                ['type' => 'scalar'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'scalar' but Closure object is not."],
            ],
            'callable' => [
                42,
                ['type' => 'callable'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'callable' but 42 is not."],
            ],
            'iterable' => [
                42,
                ['type' => 'iterable'],
                [Type::NOT_OF_TYPE => "Must be of PHP type 'iterable' but 42 is not."],
            ],
        ];
    }

    public function testInvalidType()
    {
        $type = 'foo';

        $this->expectExceptionMessageMatches("/^Invalid 'type': .+? but is {$type}\.$/");

        new Type([ 'type' => $type ]);
    }
}
