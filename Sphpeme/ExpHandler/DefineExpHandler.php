<?php

namespace Sphpeme\ExpHandler;



use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class DefineExpHandler implements ExpHandler
{
    private $defineSymbol;

    public function __construct()
    {
        $this->defineSymbol = Symbol::make('define');
    }

    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        list($_, $symbol, $exp) = $exp;
        $env->$symbol = $evaluate($exp, $env);
    }

    public function handles($exp): bool
    {
        return $exp[0] === $this->defineSymbol;
    }
}