<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env\EnvInterface;
use Sphpeme\Evaluator;
use Sphpeme\Scalar;

class ScalarHandler implements ExpHandler
{
    /**
     * @param Scalar $exp
     * @param EnvInterface $env
     * @param Evaluator $evaluate
     * @return mixed
     */
    public function evaluate($exp, EnvInterface $env, Evaluator $evaluate)
    {
        return $exp->getValue();
    }

    public function handles($exp): bool
    {
        return $exp instanceof Scalar;
    }
}