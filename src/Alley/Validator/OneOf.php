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

use Laminas\Validator\Exception\InvalidArgumentException;
use Laminas\Validator\InArray;

final class OneOf extends BaseValidator
{
    public const NOT_ONE_OF = 'notOneOf';

    protected $messageTemplates = [
        self::NOT_ONE_OF => "Must be one of %haystack% but is %value%.",
    ];

    protected $messageVariables = [
        'haystack' => ['options' => 'haystack'],
    ];

    private InArray $origin;

    public static function create(array $haystack): self
    {
        return new self(['origin' => new InArray([
            'haystack' => $haystack,
            'strict' => InArray::COMPARE_STRICT,
        ])]);
    }

    protected function testValue($value): void
    {
        if (! $this->origin->isValid($value)) {
            $this->error(self::NOT_ONE_OF);
        }
    }

    protected function setOrigin(InArray $origin)
    {
        foreach ($origin->getHaystack() as $value) {
            if (! \is_scalar($value)) {
                throw new InvalidArgumentException(
                    'Haystack must contain only scalar values but contains ' . strtoupper(\gettype($value))
                );
            }
        }

        $this->origin = $origin;
    }

    public function __get($name)
    {
        if ('options' === $name) {
            return ['haystack' => $this->origin->getHaystack()];
        }

        return null;
    }
}
