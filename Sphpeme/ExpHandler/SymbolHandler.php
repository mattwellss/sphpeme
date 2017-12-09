<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class SymbolHandler implements ExpHandler
{
    public function evaluate($exp, Env\EnvInterface $env, Evaluator $evaluate)
    {
        return $env->$exp;
    }

    public function handles($exp): bool
    {
        return $exp instanceof Symbol;
    }
}