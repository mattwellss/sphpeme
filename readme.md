# Sphpeme

A dumb slow Scheme running inside php

[![Scrutinizer Code
Quality](https://scrutinizer-ci.com/g/mattwellss/sphpeme/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mattwellss/sphpeme/?branch=master)

## Usage

The file `sphpeme.php` is autoloaded, so all the functions therein
there are available.

### Example

```php
<?php

use Sphpeme\EnvExtender\AggregateEnvExtender;
use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\ExpHandler\IfExpHandler;
use Sphpeme\ExpHandler\LambdaExpHandler;
use Sphpeme\ExpHandler\LetHandler;
use Sphpeme\ExpHandler\LetStarHandler;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\ExpHandler\SymbolHandler;
use Sphpeme\Reader;

require __DIR__ . '/vendor/autoload.php';

$env = new \Sphpeme\Env\StdEnv();
$reader = Reader::fromFilepath(__DIR__ . '/fib.scm');
$parsedLib = $reader->read();

// Evaluator with ORDERED expression handlers
$eval = new \Sphpeme\Evaluator(
    new SymbolHandler(),
    new ScalarHandler(),
    new LambdaExpHandler(new AggregateEnvExtender()),
    new IfExpHandler(),
    new DefineExpHandler(),
    new LetHandler(),
    new LetStarHandler()
);

// Evaluate the library, adding its definitions to our env
$eval($parsedLib, $env);

$fakeInput = '(fib 15)';
$fake = fopen('php://memory', 'w+b');
fwrite($fake, $fakeInput);
rewind($fake);
// Evaluate some other code, which uses the updated env
$program = Reader::fromStream($fake)->read();
$time = microtime(true);
$eval($program, $env);
echo microtime(true) - $time;
```

## Tests

Near all of `sphpeme.php` is covered by tests. Tests can be run by
calling phpunit after comoposer installation:
```sh
$ ./vendor/bin/phpunit
```

## Todo

### Speed

While it's unlikely that sphpeme will ever exist outside the context
of "toy," faster is better! Evaluation and tokenization are two areas
ripe for improvement.

#### Ideas

 - Fewer function calls
 - "Flattener" for envs, eliminating linear search for a match

### Features

#### `letrec`, named `let`

With the addition of `let*` and `let`, many local definition needs are
covered. However, named `let` will be required for looping purposes.

#### `cond`

Better than nested `if`!!!


#### m-m-m-macros!

maybe.

## Credits

Much of the work here is simply a translation of Peter Norvig's
excellent article, [*(How to Write a (Lisp) Interpreter
in(Python))*](http://norvig.com/lispy.html)
