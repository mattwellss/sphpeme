<?php

namespace Sphpeme;


class SymbolHandler implements ExpHandler
{
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        return $env->$exp;
    }

    public function handles($exp): bool
    {
        return $exp instanceof Symbol;
    }
}