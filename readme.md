# Sphpeme

A dumb slow Scheme running inside php

## Usage

The file `sphpeme.php` is autoloaded, so all the functions therein there are
available.

### Example

```php
<?php

use function Sphpeme\{
    get_std_env, evaluate, parse, tokenize
};

require __DIR__ . '/vendor/autoload.php';
$env = get_std_env();
$program = file_get_contents(__DIR__ . '/test.scm');

evaluate(parse(tokenize($program)), $env);

```

Since the `$env` given to an `evaluate` call is mutated during eval, functions
defined by a scheme library can be "imported" into an env:

```php
$env = get_std_env();
$program = file_get_contents(__DIR__ . '/library.scm');

evaluate(parse(tokenize($program)), $env);

$result = evaluate(parse(tokenize('(lib-fn)')), $env);

```

## Tests

Near all of `sphpeme.php` is covered by tests. Tests can be run by
calling phpunit after comoposer installation:
```sh
$ ./vendor/bin/phpunit
```

## Todo

### Speed

While it's unlikely that sphpeme will ever exist outside the context of "toy,"
but faster is better! Evaluation and tokenization are two areas ripe for
improvement.

### Features

#### `let`, `let*`, etc

I like using these instead of flopping around in a much of lambdas, so they
should be added

#### `cond`

Better than nested `if`!!!


#### m-m-m-macros!

maybe.

### Modularity

The std env is pretty messy now, and should probably be separated by category.
