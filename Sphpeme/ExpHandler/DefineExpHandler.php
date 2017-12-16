<?php

namespace Sphpeme\ExpHandler;



use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class DefineExpHandler implements ExpHandler
{
    private $defineSymbol;
    private $lambdaSymbol;

    public function __construct()
    {
        $this->defineSymbol = Symbol::make('define');
        $this->lambdaSymbol = Symbol::make('lambda');
    }

    public function evaluate($exp, Env\EnvInterface $env, Evaluator $evaluate)
    {
        $symbol = $exp[1];

        if (\is_array($symbol)) {
            $args = \array_slice($symbol, 1);
            $symbol = $symbol[0];
            $define = array_merge(
                [$this->lambdaSymbol, $args], \array_slice($exp, 2));
        } else {
            $define = $exp[2];
        }

        $env->$symbol = $evaluate($define, $env);
    }

    public function handles($exp): bool
    {
        return $exp[0] === $this->defineSymbol;
    }
}