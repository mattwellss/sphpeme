<?php

namespace Sphpeme;


class Evaluator
{
    /**
     * @var Evaluator\SpecialForm[]
     */
    private $specialForms;

    public function __construct(Evaluator\SpecialForm ...$specialForms)
    {
        $this->specialForms = $specialForms;
    }

    public function __invoke($exp, Env $env)
    {
        if ($exp instanceof Symbol) {
            return $env->$exp;
        }

        if (\is_numeric($exp) || \is_string($exp)) {
            return $exp;
        }

        foreach ($this->specialForms as $specialForm) {
            if ($specialForm->handles($exp)) {
                return $specialForm->evaluate($exp, $env, $this);
            }
        }

        $call = $this($exp[0], $env);
        $args = [];
        foreach (\array_slice($exp, 1) as $arg) {
            $args[] = $this($arg, $env);
        }

        return $call(...$args);
    }
}