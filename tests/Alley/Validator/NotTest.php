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

final class NotTest extends TestCase
{
    public function testNegation()
    {
        $origin = new AlwaysValid();
        $actual = new Not($origin, 'foo');
        $value = 42;
        $this->assertNotSame($actual->isValid($value), $origin->isValid($value));
        $this->assertCount(1, $actual->getMessages());
    }
}
