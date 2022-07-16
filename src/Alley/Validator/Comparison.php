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

use Laminas\Validator\Exception;

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

    private const OPERATOR_ERRORS = [
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

    /**
     * @var mixed
     */
    protected $compared;

    private string $operator = '===';

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
        'compared' => 'compared',
    ];

    protected function testValue($value): void
    {
        switch ($this->operator) {
            case '==':
                $result = $value == $this->compared;
                break;
            case '!=':
            case '<>':
                $result = $value != $this->compared;
                break;
            case '!==':
                $result = $value !== $this->compared;
                break;
            case '<':
                $result = $value < $this->compared;
                break;
            case '>':
                $result = $value > $this->compared;
                break;
            case '<=':
                $result = $value <= $this->compared;
                break;
            case '>=':
                $result = $value >= $this->compared;
                break;
            case '===':
            default:
                $result = $value === $this->compared;
                break;
        }

        if (! $result) {
            $this->error(self::OPERATOR_ERRORS[$this->operator]);
        }
    }

    protected function setCompared($compared)
    {
        $this->compared = $compared;
    }

    protected function setOperator(string $operator)
    {
        $oneOf = OneOf::create(self::SUPPORTED_OPERATORS);
        $valid = $oneOf->isValid($operator);

        if (! $valid) {
            throw new Exception\InvalidArgumentException($oneOf->getMessages()[0]);
        }

        $this->operator = $operator;
    }
}
