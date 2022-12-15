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

use Laminas\Validator\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DivisibleByTest extends TestCase
{
    public function testValidInput()
    {
        $validator = new DivisibleBy(['divisor' => 3]);
        $this->assertTrue($validator->isValid(42));
        $this->assertTrue($validator->isValid('42'));
    }

    public function testInvalidInput()
    {
        $validator = new DivisibleBy(['divisor' => 3]);
        $this->assertFalse($validator->isValid(43));
        $this->assertSame(
            ['notDivisibleBy' => 'Must be evenly divisible by 3 but 43 is not.'],
            $validator->getMessages(),
        );
    }

    public function testZeroDivisor()
    {
        $this->expectException(InvalidArgumentException::class);
        new DivisibleBy(['divisor' => 0]);
    }
}
