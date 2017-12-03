<?php

namespace Sphpeme;


class ScalarHandler implements ExpHandler
{
    public function evaluate($exp, Env $env, Evaluator $evaluate)
    {
        return $exp;
    }

    public function handles($exp): bool
    {
        return \is_numeric($exp) || \is_string($exp);
    }
}