# Laminas Validator Extensions

This package provides additional validation classes for [the Laminas Validator framework](https://docs.laminas.dev/laminas-validator/), plus a custom base validation class.

For more information about what validators do, how to use them, and how to write your own, [visit the Laminas documentation](https://docs.laminas.dev/laminas-validator/). 

## Base Validator

The abstract `Alley\Validator\BaseValidator` class standardizes the implementation of custom validators with `\Laminas\Validator\AbstractValidator`.

When extending `BaseValidator`, validation logic goes into a new `testValue()` method, which is responsible only for applying the logic and adding any validation errors. It's no longer necessary to call `setValue()` prior to evaluating the input, and `isValid()` will return `true` if there are no failure messages after evaluating the input and `false` if there are any messages.

Before:

```php
<?php

class Float extends \Laminas\Validator\AbstractValidator
{
    const FLOAT = 'float';

    protected $messageTemplates = [
        self::FLOAT => "'%value%' is not a floating point value",
    ];

    public function isValid($value)
    {
        $this->setValue($value);

        if (! is_float($value)) {
            $this->error(self::FLOAT);
            return false;
        }

        return true;
    }
}
```

After:

```php
<?php

class Float extends \Alley\Validator\BaseValidator
{
    const FLOAT = 'float';

    protected $messageTemplates = [
        self::FLOAT => "'%value%' is not a floating point value",
    ];

    public function testValue($value): void
    {
        if (! is_float($value)) {
            $this->error(self::FLOAT);
        }
    }
}
```

## Validators

### `AlwaysValid`

`\Alley\Validator\AlwaysValid` marks all input as valid. It can be used to satisfy type requirements when full validation needs to be disabled or is impractical.

#### Supported options

None.

#### Basic usage

```php
<?php

$valid = new \Alley\Validator\AlwaysValid();
$valid->isValid(42); // true
$valid->isValid(false); // true
$valid->isValid('abcdefghijklmnopqrstuvwxyz'); // true
```

### `Comparison`

`\Alley\Validator\Comparison` compares input to another value using a PHP [comparison operator](https://www.php.net/manual/en/language.operators.comparison.php). The input passes validation if the comparison is true. Input is placed on the left side of the operator.

#### Supported options

The following options are supported for `\Alley\Validator\Comparison`:

- `compared`: The value the inputs are compared to. It is placed on the right side of the operator.
- `operator`: The PHP comparison operator used to compare the input and `compared`.

#### Basic usage

```php
<?php

$valid = new \Alley\Validator\Comparison(
    [
        'operator' => '<=',
        'compared' => 100,
    ]
);
$valid->isValid(101); // false

$valid = new \Alley\Validator\Comparison(
    [
        'operator' => '!==',
        'compared' => false,
    ]
);
$valid->isValid(true); // true
```

### `OneOf`

`Alley\Validator\OneOf` validates whether an array of scalar values of contains the input. The input passes validation if it is found within the array.

`OneOf` is a simpler version of `\Laminas\Validator\InArray` that accepts only scalar values in the haystack and does only strict comparisons. In return, it produces a friendlier error message that lists the allowed values.

`OneOf` contains a `::create()` named constructor for initializing a validator directly from the haystack.

#### Supported Options

The following options are supported for `\Alley\Validator\OneOf`:

- `haystack`: The array to be searched for the input.

#### Basic Usage

```php
<?php

$valid = \Alley\Validator\OneOf::create(['one', 'two', 'three']);
$valid->isValid('four'); // false
$valid->getMessages(); // ['notOneOf' => 'Must be one of [one, two, three] but is four.']
```

### `Type`

`\Alley\Validator\Type` allows you to validate whether the input is of the given PHP type. The input passes if it is the expected type.

This validator is inspired by PHPUnit's `\PHPUnit\Framework\Constraint\IsType` class.

#### Supported options

The following options are supported for `\Alley\Validator\Type`:

- `type`: The expected PHP type. Supported types are `array`, `bool`, `boolean`, `callable`, `double`, `float`, `int`, `integer`, `iterable`, `null`, `numeric`, `object`, `real`, `resource`, `string`, and `scalar`.

#### Basic usage

```php
<?php

$valid = new \Alley\Validator\Type(['type' => 'callable']);
$valid->isValid('date_create_immutable'); // true

$valid = new \Alley\Validator\Type(['type' => 'bool']);
$valid->isValid([]); // false
```
