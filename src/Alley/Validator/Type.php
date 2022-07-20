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
use Laminas\Validator\ValidatorInterface;

final class Type extends BaseValidator
{
    public const NOT_OF_TYPE = 'notOfType';

    private const SUPPORTED_TYPES = [
        'array',
        'bool',
        'boolean',
        'double',
        'float',
        'int',
        'integer',
        'null',
        'numeric',
        'object',
        'real',
        'resource',
        'string',
        'scalar',
        'callable',
        'iterable',
    ];

    protected $messageTemplates = [
        self::NOT_OF_TYPE => "Must be of PHP type '%type%' but %value% is not.",
    ];

    protected $messageVariables = [
        'type' => ['options' => 'type'],
    ];

    protected $options = [
        'type' => 'null',
    ];

    private ValidatorInterface $typeValidator;

    public function __construct($options = null)
    {
        $this->typeValidator = new OneOf(['haystack' => self::SUPPORTED_TYPES]);

        parent::__construct($options);
    }

    protected function testValue($value): void
    {
        switch ($this->type) {
            case 'array':
                $result = \is_array($value);
                break;
            case 'bool':
            case 'boolean':
                $result = \is_bool($value);
                break;
            case 'int':
            case 'integer':
                $result = \is_int($value);
                break;
            case 'double':
            case 'float':
            case 'real':
                $result = \is_float($value);
                break;
            case 'numeric':
                $result = is_numeric($value);
                break;
            case 'object':
                $result = \is_object($value);
                break;
            case 'resource':
                $result = \is_resource($value);
                break;
            case 'string':
                $result = \is_string($value);
                break;
            case 'scalar':
                $result = \is_scalar($value);
                break;
            case 'callable':
                $result = \is_callable($value);
                break;
            case 'iterable':
                $result = is_iterable($value);
                break;
            case 'null':
            default:
                $result = \is_null($value);
                break;
        }

        if (!$result) {
            $this->error(self::NOT_OF_TYPE);
        }
    }

    protected function setType(string $type)
    {
        $valid = $this->typeValidator->isValid($type);

        if (! $valid) {
            throw new InvalidArgumentException($this->typeValidator->getMessages()[0]);
        }

        $this->options['type'] = $type;
    }
}
