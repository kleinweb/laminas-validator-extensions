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

final class OneOfTest extends TestCase
{
    public function testValidInput()
    {
        $validator = OneOf::create(['a', 'b', 'c']);
        $this->assertTrue($validator->isValid('a'));
    }

    public function testInvalidInput()
    {
        $validator = OneOf::create(['a', 'b', 'c']);
        $this->assertFalse($validator->isValid('z'));
        $this->assertSame(
            ['notOneOf' => 'Must be one of [a, b, c] but is z.'],
            $validator->getMessages(),
        );
    }
}
