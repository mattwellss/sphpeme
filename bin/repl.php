<?php

namespace Sphpeme;

use Sphpeme\Env\StdEnv;
use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\ExpHandler\IfExpHandler;
use Sphpeme\ExpHandler\LambdaExpHandler;
use Sphpeme\ExpHandler\LetHandler;
use Sphpeme\ExpHandler\LetStarHandler;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\ExpHandler\SymbolHandler;

require __DIR__ . '/../vendor/autoload.php';

$env = new StdEnv();
$eval = new Evaluator(
    new SymbolHandler(),
    new ScalarHandler(),
    new LambdaExpHandler(new EnvExtender\AggregateEnvExtender()),
    new IfExpHandler(),
    new DefineExpHandler(),
    new LetHandler(),
    new LetStarHandler()
);

$reader = Reader::fromStream(STDIN);
do {
    try {
        echo PHP_EOL . '> ';
        $program = $reader->read();
        if (!$program) {
            echo 'Bye!' . PHP_EOL;
            exit;
        }
        var_export($eval($program, $env));
    } catch (\Throwable $t) {
        echo 'Exception occured: ' . $t->getMessage() . PHP_EOL;
    }
} while (true);

