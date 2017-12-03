<?php

namespace Sphpeme;


use Sphpeme\ExpHandler;

class DefineExpHandler implements ExpHandler
{
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        [$_, $symbol, $exp] = $exp;
        $env->$symbol = $evaluate($exp, $env);
    }

    public function handles($exp): bool
    {
        return $exp[0] == 'define';
    }
}