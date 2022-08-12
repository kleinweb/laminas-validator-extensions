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

use Laminas\Validator\GreaterThan;
use PHPUnit\Framework\TestCase;

final class AnyValidatorTest extends TestCase
{
    public function testNoValidators()
    {
        $validator = new AnyValidator();
        $this->assertTrue($validator->isValid(42));
    }

    public function testValidValidator()
    {
        $validator = new AnyValidator();
        $validator->attach(new AlwaysValid());
        $this->assertTrue($validator->isValid(42));
    }

    public function testInvalidValidator()
    {
        $validator = new AnyValidator();
        $validator->attach(new GreaterThan(['min' => 43]));
        $this->assertFalse($validator->isValid(42));
    }

    public function testFirstValidValidator()
    {
        $validator = new AnyValidator();
        $validator->attach(new AlwaysValid());
        $validator->attach(new GreaterThan(['min' => 43]));
        $this->assertTrue($validator->isValid(42));
    }
}
