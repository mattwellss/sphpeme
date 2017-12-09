<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class IfExpHandler implements ExpHandler
{
    private $ifSymbol;

    public function __construct()
    {
        $this->ifSymbol = Symbol::make('if');
    }

    public function evaluate($exp, Env\EnvInterface $env, Evaluator $evaluate)
    {
        list($if, $test, $true, $false) = $exp;

        return $evaluate(
            $evaluate($test, $env)
                ? $true
                : $false,
            $env);
    }

    public function handles($exp): bool
    {
        return $exp[0] === $this->ifSymbol;
    }
}