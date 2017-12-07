<?php

namespace Sphpeme;

use Sphpeme\ExpHandler\DefineExpHandler;
use Sphpeme\ExpHandler\IfExpHandler;
use Sphpeme\ExpHandler\LambdaExpHandler;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\ExpHandler\SymbolHandler;

require __DIR__ . '/../vendor/autoload.php';

$env = get_std_env();
$eval = new Evaluator(
    new SymbolHandler(),
    new ScalarHandler(),
    new LambdaExpHandler(),
    new IfExpHandler(),
    new DefineExpHandler()
);

$reader = Reader::fromStream(STDIN);
echo "\n> ";
while ($program = $reader->read()) {
    var_export($eval($program, $env));
    echo PHP_EOL . '> ';
}