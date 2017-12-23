<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class NamedLetHandler implements ExpHandler
{


    /** @var Symbol */
    private $letSymbol;

    public function __construct()
    {
        $this->letSymbol = Symbol::make('let');
    }

    public function handles($program): bool
    {
        return $program[0] === $this->letSymbol &&
            $program[1] instanceof Symbol;
    }

    public function evaluate($program, Env\EnvInterface $env, Evaluator $evaluate)
    {
        // generate a lambda (eval the program *without* name)
        // use that as the value in the define
        // then call that
        list($_let, $name, $bindings) = $program;
        $body = \array_slice($program, 3);
        $lambdaExp = array_merge([Symbol::make('lambda'), array_column($bindings, 0)], $body);
        $defineExp = [Symbol::make('define'), $name, $lambdaExp];
        $evaluate($defineExp, $env);
        return $evaluate(array_merge([$name], array_column($bindings, 1)), $env);
    }
}