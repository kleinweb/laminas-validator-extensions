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

use Laminas\Validator\ValidatorInterface;

final class Not implements ValidatorInterface
{
    private ValidatorInterface $origin;

    private string $message;

    public function __construct(ValidatorInterface $origin, string $message)
    {
        $this->origin = $origin;
        $this->message = $message;
    }

    public function isValid($value)
    {
        return !$this->origin->isValid($value);
    }

    public function getMessages()
    {
        $messages = [];

        if (\count($this->origin->getMessages()) === 0) {
            $messages[] = $this->message;
        }

        return $messages;
    }
}
