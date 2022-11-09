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

final class ContainsStringTest extends TestCase
{
    public function testValidInput()
    {
        $validator = new ContainsString(['needle' => 'Foo']);
        $this->assertTrue($validator->isValid('Foobar'));
        $this->assertFalse($validator->isValid('foobar'));
    }

    public function testIgnoreCase()
    {
        $validator = new ContainsString(['needle' => 'foo', 'ignoreCase' => true]);
        $this->assertTrue($validator->isValid('Foobar'));
        $this->assertTrue($validator->isValid('foobar'));
    }

    public function testInvalidInput()
    {
        $validator = new ContainsString(['needle' => 'baz']);
        $this->assertFalse($validator->isValid('foobar'));
        $this->assertSame(
            ['notContainsString' => 'Must contain string "baz".'],
            $validator->getMessages(),
        );
    }
}
