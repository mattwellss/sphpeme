<?php

namespace Sphpeme\Evaluator;


use Sphpeme\Env;
use Sphpeme\Evaluator;

interface SpecialForm
{
    public function handles($program): bool;
    public function evaluate($program, Env $env, Evaluator $evaluate);
}