<?php

namespace Sphpeme;


use Sphpeme\Env;
use Sphpeme\Evaluator;

interface ExpHandler
{
    public function handles($program): bool;
    public function evaluate($program, Env $env, Evaluator $evaluate);
}