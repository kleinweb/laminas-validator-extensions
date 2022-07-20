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

use Laminas\Validator\Callback;
use Laminas\Validator\Exception\InvalidArgumentException;
use Laminas\Validator\Explode;
use Laminas\Validator\InArray;
use Laminas\Validator\ValidatorInterface;

final class OneOf extends BaseValidator
{
    public const NOT_ONE_OF = 'notOneOf';

    protected $messageTemplates = [
        self::NOT_ONE_OF => "Must be one of %haystack% but is %value%.",
    ];

    protected $messageVariables = [
        'haystack' => ['options' => 'haystack'],
    ];

    protected $options = [
        'origin' => null,
        'haystack' => [],
    ];

    private ValidatorInterface $haystackValidator;

    public function __construct($options = null)
    {
        $this->haystackValidator = new Explode([
            'validator' => new Callback('is_scalar'),
            'breakOnFirstFailure' => true,
        ]);

        parent::__construct($options);
    }

    public static function create(array $haystack): self
    {
        return new self(['origin' => new InArray([
            'haystack' => $haystack,
            'strict' => InArray::COMPARE_STRICT,
        ])]);
    }

    protected function testValue($value): void
    {
        if (! $this->options['origin']) {
            throw new InvalidArgumentException('No haystack given');
        }

        if (! $this->options['origin']->isValid($value)) {
            $this->error(self::NOT_ONE_OF);
        }
    }

    protected function setOrigin(InArray $origin)
    {
        $haystack = $origin->getHaystack();
        $valid = $this->haystackValidator->isValid($haystack);

        if (! $valid) {
            throw new InvalidArgumentException('Haystack must contain only scalar values.');
        }

        $this->options['origin'] = $origin;
        $this->options['haystack'] = $haystack;
    }
}
