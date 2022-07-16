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

use Laminas\Validator\AbstractValidator;

abstract class BaseValidator extends AbstractValidator
{
    final public function isValid($value)
    {
        $this->setValue($value);
        $this->testValue($value);
        return \count($this->getMessages()) === 0;
    }

    abstract protected function testValue($value): void;
}
