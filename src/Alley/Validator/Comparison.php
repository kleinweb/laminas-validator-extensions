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

final class Comparison extends BaseValidator
{
    private const SUPPORTED_OPERATORS = [
        '==',
        '===',
        '!=',
        '<>',
        '!==',
        '<',
        '>',
        '<=',
        '>=',
    ];

    private const OPERATOR_ERROR_CODES = [
        '==' => 'notEqual',
        '===' => 'notIdentical',
        '!=' => 'isEqual',
        '<>' => 'isEqual',
        '!==' => 'isIdentical',
        '<' => 'notLessThan',
        '>' => 'notGreaterThan',
        '<=' => 'notLessThanOrEqualTo',
        '>=' => 'notGreaterThanOrEqualTo',
    ];

    protected $messageTemplates = [
        'notEqual' => 'Must be equal to %compared% but is %value%.',
        'notIdentical' => 'Must be identical to %compared% but is %value%.',
        'isEqual' => 'Must not be equal to %compared% but is %value%.',
        'isIdentical' => 'Must not be identical to %compared%.',
        'notLessThan' => 'Must be less than %compared% but is %value%.',
        'notGreaterThan' => 'Must be greater than %compared% but is %value%.',
        'notLessThanOrEqualTo' => 'Must be less than or equal to %compared% but is %value%.',
        'notGreaterThanOrEqualTo' => 'Must be greater than or equal to %compared% but is %value%.',
    ];

    protected $messageVariables = [
        'compared' => ['options' => 'compared'],
    ];

    protected $options = [
        'compared' => null,
        'operator' => '===',
    ];

    private ValidatorInterface $operatorOptionValidator;

    public function __construct($options = null)
    {
        $this->operatorOptionValidator = new OneOf(['haystack' => self::SUPPORTED_OPERATORS]);

        parent::__construct($options);
    }

    protected function testValue($value): void
    {
        switch ($this->options['operator']) {
            case '==':
                $result = $value == $this->options['compared'];
                break;
            case '!=':
            case '<>':
                $result = $value != $this->options['compared'];
                break;
            case '!==':
                $result = $value !== $this->options['compared'];
                break;
            case '<':
                $result = $value < $this->options['compared'];
                break;
            case '>':
                $result = $value > $this->options['compared'];
                break;
            case '<=':
                $result = $value <= $this->options['compared'];
                break;
            case '>=':
                $result = $value >= $this->options['compared'];
                break;
            case '===':
            default:
                $result = $value === $this->options['compared'];
                break;
        }

        if (! $result) {
            $this->error(self::OPERATOR_ERROR_CODES[$this->options['operator']]);
        }
    }

    protected function setOperator(string $operator)
    {
        $valid = $this->operatorOptionValidator->isValid($operator);

        if (! $valid) {
            throw new InvalidArgumentException($this->operatorOptionValidator->getMessages()[0]);
        }

        $this->options['operator'] = $operator;
    }
}
