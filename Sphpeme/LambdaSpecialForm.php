<?php

namespace Sphpeme;


use Sphpeme\Evaluator\SpecialForm;

class LambdaSpecialForm implements SpecialForm
{
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        [$lambda, $params, $body] = $exp;
        return function (...$args) use ($env, $body, $params, $evaluate) {
            return $evaluate($body, env_extend($env, array_combine($params, $args)));
        };
    }

    public function handles($exp): bool
    {
        return $exp[0] == 'lambda';
    }
}