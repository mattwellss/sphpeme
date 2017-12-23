<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class CondHandler implements ExpHandler
{

    private $condSymbol;

    private $ifSymbol;

    private $elseSymbol;

    public function __construct()
    {
        $this->elseSymbol = Symbol::make('else');
        $this->condSymbol = Symbol::make('cond');
        $this->ifSymbol = Symbol::make('if');
    }

    public function handles($program): bool
    {
        return $program[0] === $this->condSymbol;
    }

    public function evaluate($program, Env\EnvInterface $env, Evaluator $evaluate)
    {
        return $evaluate($this->makeIfExp(\array_slice($program, 1)), $env);
    }

    private function makeIfExp($conditions)
    {
        $pred = $conditions[0][0];

        if ($pred === $this->elseSymbol) {
            return $conditions[0][1];
        }

        return [$this->ifSymbol, $pred,
            $conditions[0][1], $this->makeIfExp(\array_slice($conditions, 1))];
    }
}