<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use function Sphpeme\env_extend;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class LambdaExpHandler implements ExpHandler
{
    private $lambdaSymbol;

    public function __construct()
    {
        $this->lambdaSymbol = Symbol::make('lambda');
    }

    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        list($lambda, $params, $body) = $exp;
        return function (...$args) use ($env, $body, $params, $evaluate) {
            return $evaluate($body, env_extend($env, array_combine($params, $args)));
        };
    }

    public function handles($exp): bool
    {
        return $exp[0] === $this->lambdaSymbol;
    }
}