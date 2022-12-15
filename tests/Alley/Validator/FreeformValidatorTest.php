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

final class FreeformValidatorTest extends TestCase
{
    public function testSampleImplementation()
    {
        $actual = new class () extends FreeformValidator {
            protected function testValue($value): void
            {
                $fizz = new DivisibleBy(['divisor' => 3]);
                $buzz = new DivisibleBy(['divisor' => 5]);
                $fizzbuzz = new FastFailValidatorChain([$fizz, $buzz]);

                if ($fizz->isValid($value)) {
                    $this->error('fizz', 'Fizz');
                }

                if ($buzz->isValid($value)) {
                    $this->error('buzz', 'Buzz');
                }

                if ($fizzbuzz->isValid($value)) {
                    $this->error('fizzbuzz', 'FizzBuzz');
                }
            }
        };

        $this->assertFalse($actual->isValid(9));
        $this->assertSame(['fizz' => 'Fizz'], $actual->getMessages());

        $this->assertTrue($actual->isValid(1));
        $this->assertSame([], $actual->getMessages());

        $this->assertFalse($actual->isValid(15));
        $this->assertSame(['fizz' => 'Fizz', 'buzz' => 'Buzz', 'fizzbuzz' => 'FizzBuzz'], $actual->getMessages());
    }
}
