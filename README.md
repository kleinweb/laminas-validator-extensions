# Laminas Validator Extensions

This package provides additional validation classes for [the Laminas Validator framework](https://docs.laminas.dev/laminas-validator/), plus a custom base validation class.

## Installation

Install the latest version with:

```bash
$ composer require alleyinteractive/laminas-validator-extensions
```

## Basic usage

For more information about what validators do, how to use them, and how to write your own, [visit the Laminas documentation](https://docs.laminas.dev/laminas-validator/).

## Base validator

The abstract `Alley\Validator\BaseValidator` class standardizes the implementation of custom validators with `\Laminas\Validator\AbstractValidator`.

When extending `BaseValidator`, validation logic goes into a new `testValue()` method, which is responsible only for applying the logic and adding any validation errors. It's no longer necessary to call `setValue()` prior to evaluating the input, and `isValid()` will return `true` if there are no error messages after evaluating the input and `false` if there are any messages.

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

## "Any Validator" chains

`\Alley\Validator\AnyValidator` is like a [Laminas validator chain](https://docs.laminas.dev/laminas-validator/validator-chains/) except that it connects the validators with "OR," marking input as valid as soon it passes one of the given validators.

Unlike a Laminas validator chain, validators can only be attached, not prepended, and there is no `$priority` argument.

### Basic usage

```php
<?php

$valid = new \Alley\Validator\AnyValidator([new \Laminas\Validator\LessThan(['max' => 10])]);
$valid->attach(new \Laminas\Validator\GreaterThan(['min' => 90]));

$valid->isValid(9); // true
$valid->isValid(99); // true
$valid->isValid(42); // false
```

## "Fast fail" validator chains

`\Alley\Validator\FastFailValidatorChain` is like a [Laminas validator chain](https://docs.laminas.dev/laminas-validator/validator-chains/) except that if a validator fails, the chain will automatically be broken; there is no `$breakChainOnFailure` parameter.

Unlike a Laminas validator chain, validators can only be attached, not prepended, and there is no `$priority` argument.

### Basic usage

```php
$valid = new \Alley\Validator\FastFailValidatorChain([new \Laminas\Validator\LessThan(['max' => 10])]);
$valid->attach(new \Laminas\Validator\GreaterThan(['min' => 90]));

$valid->isValid(42); // false
count($valid->getMessages()); // 1
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

### `ContainsString`

`\Alley\Validator\ContainsString` is a validator around the `str_contains()` function. Each instance of the validator represents the "needle" string and validates whether the string is found within the input "haystack."  Inputs will automatically be cast to strings.

#### Supported options

The following options are supported for `\Alley\Validator\ContainsString`:

- `needle`: The string or instance of `\Stringable` the inputs are searched for. It will automatically be cast to a string at the time of validation.
- `ignoreCase`: Whether to perform a case-insensitive search. False by default.

#### Basic usage

```php
<?php

$valid = new \Alley\Validator\ContainsString(
    [
        'needle' => 'foo',
    ],
);

$valid->isValid('foobar'); // true
$valid->isValid('barbaz'); // false
```

### `DivisibleBy`

`\Alley\Validator\DivisibleBy` allows you to validate whether the input is evenly divisible by a given numeric value. Inputs will automatically be cast to integers.

#### Supported options

The following options are supported for `\Alley\Validator\DivisibleBy`:

- `divisor`: The value the inputs are divided by. It will automatically be cast to an integer.

#### Basic usage

```php
<?php

$valid = new \Alley\Validator\DivisibleBy(
    [
        'divisor' => 3,
    ],
);

$valid->isValid(9); // true
$valid->isValid(10); // false
```

### `Not`

`Alley\Validator\Not` inverts the validity of a given validator. It allows for creating validators that test whether input is, for example, "not one of" in addition to "one of."

#### Supported options

None.

#### Basic usage

```php
<?php

$origin = new \Alley\Validator\OneOf(['haystack' => ['foo', 'bar']]);
$valid = new Alley\Validator\Not($origin, 'The input was invalid.');

$valid->isValid('foo'); // false
$valid->isValid('baz'); // true
```

### `OneOf`

`Alley\Validator\OneOf` validates whether an array of scalar values contains the input.

`OneOf` is a simpler version of `\Laminas\Validator\InArray` that accepts only scalar values in the haystack and does only strict comparisons. In return, it produces a friendlier error message that lists the allowed values.

#### Supported options

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

### `WithMessage`

`Alley\Validator\WithMessage` allows you to decorate a validator with a custom failure code and message, replacing the validator's usual failure messages.

#### Supported options

None.

#### Basic usage

```php
<?php

$origin = new \Laminas\Validator\GreaterThan(42);
$valid = new Alley\Validator\WithMessage('tooSmall', 'Please enter a number greater than 42.', $origin);

$valid->isValid(41); // false
$valid->getMessages(); // ['tooSmall' => 'Please enter a number greater than 42.']
```

## About

### License

[GPL-2.0-or-later](https://github.com/alleyinteractive/laminas-validator-extensions/blob/main/LICENSE)

### Maintainers

[Alley Interactive](https://github.com/alleyinteractive)
