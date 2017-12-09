<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class LetHandler implements ExpHandler
{


    private $letSymbol;
    private $lambdaSymbol;

    public function __construct()
    {
        $this->letSymbol = Symbol::make('let');
        $this->lambdaSymbol = Symbol::make('lambda');
    }

    public function handles($program): bool
    {
        return $program[0] === $this->letSymbol;
    }

    public function evaluate($program, Env\EnvInterface $env, Evaluator $evaluate)
    {
        // bindings is an array of pairs: [[param, value], [param, value]]
        list($_let, $bindings) = $program;
        $letBody = \array_slice($program, 2);
        $lambdaExp = array_merge(
            [$this->lambdaSymbol, array_column($bindings, 0)],
            $letBody); // unpack the body of let into the lambda exp
        return $evaluate(array_merge([$lambdaExp], array_column($bindings, 1)), $env);
    }
}