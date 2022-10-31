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
use Laminas\Validator\LessThan;
use PHPUnit\Framework\TestCase;

final class FastValidatorChainTest extends TestCase
{
    public function testNoValidators()
    {
        $validator = new FastValidatorChain([]);
        $this->assertTrue($validator->isValid(42));
    }

    public function testValidValidator()
    {
        $validator = new FastValidatorChain([new AlwaysValid(), new LessThan(['max' => 43])]);
        $this->assertTrue($validator->isValid(42));
    }

    public function testInvalidValidator()
    {
        $validator = new FastValidatorChain([new AlwaysValid(), new GreaterThan(['min' => 43])]);
        $this->assertFalse($validator->isValid(42));
    }

    public function testBreakChainOnFirstFailure()
    {
        $validator = new FastValidatorChain([
            new LessThan(['max' => 10]),
            new GreaterThan(['min' => 43]),
        ]);
        $this->assertFalse($validator->isValid(42));
        $this->assertCount(1, $validator->getMessages());
    }
}
