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

use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\ExpHandler\IfExpHandler;
use Sphpeme\ExpHandler\LambdaExpHandler;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\ExpHandler\SymbolHandler;
use Sphpeme\Reader;


require __DIR__ . '/vendor/autoload.php';

$env = new \Sphpeme\Env\StdEnv();
$reader = Reader::fromFilepath('examples/fib.scm');
$parsedLib = $reader->read();

// Evaluator with ORDERED expression handlers
$eval = new \Sphpeme\Evaluator(
    new SymbolHandler(),
    new ScalarHandler(),
    new LambdaExpHandler(new \Sphpeme\EnvExtender\AggregateEnvExtender()),
    new IfExpHandler(),
    new DefineExpHandler()
);

// Evaluate the library, adding its definitions to our env
$eval($parsedLib, $env);

$fake = fopen('php://memory', 'w+b');
fwrite($fake, '(fib 15)');
rewind($fake);

// Evaluate some other code, which uses the updated env
echo $eval(Reader::fromStream($fake)->read(), $env);

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

#### `let*`, `letrec`

I like using these instead of flopping around in a mess of lambdas, so
they should be added
*n.b.* `let` is added 

#### `cond`

Better than nested `if`!!!


#### m-m-m-macros!

maybe.

## Credits

Much of the work here is simply a translation of Peter Norvig's
excellent article, [*(How to Write a (Lisp) Interpreter
in(Python))*](http://norvig.com/lispy.html)
