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

final class WithMessageTest extends TestCase
{
    public function testUsesCustomMessage()
    {
        $code = 'bar';
        $message = 'baz';
        $validator = new WithMessage(
            $code,
            $message,
            new Not(new AlwaysValid(), 'foo'),
        );
        $this->assertFalse($validator->isValid('bat'));
        $this->assertSame([$code => $message], $validator->getMessages());
    }

    public function testNoMessageWhenValid()
    {
        $validator = new WithMessage(
            'foo',
            'bar',
            new AlwaysValid(),
        );
        $this->assertTrue($validator->isValid('baz'));
        $this->assertCount(0, $validator->getMessages());
    }
}
