<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class IfExpHandler implements ExpHandler
{
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        [$if, $test, $true, $false] = $exp;

        return $evaluate(
            $evaluate($test, $env)
                ? $true
                : $false,
            $env);
    }

    public function handles($exp): bool
    {
        return $exp[0] == 'if';
    }
}