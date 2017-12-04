<?php

namespace Sphpeme;


class ScalarHandler implements ExpHandler
{
    /**
     * @param Scalar $exp
     * @param Env $env
     * @param Evaluator $evaluate
     * @return mixed
     */
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        return $exp->getValue();
    }

    public function handles($exp): bool
    {
        return $exp instanceof Scalar;
    }
}