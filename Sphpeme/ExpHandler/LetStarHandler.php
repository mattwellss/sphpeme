<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class LetStarHandler implements ExpHandler
{


    private $letStarSymbol;
    private $letSymbol;

    public function __construct()
    {
        $this->letStarSymbol = Symbol::make('let*');
        $this->letSymbol = Symbol::make('let');
    }

    public function handles($program): bool
    {
        return $program[0] === $this->letStarSymbol;
    }

    public function generateLet($program)
    {
        // bindings is an array of pairs: [[param, value], [param, value]]
        list($_letStar, $bindings) = $program;
        $body = \array_slice($program, 2);

        if (\count($bindings) > 1) {
            return [$this->letSymbol, [$bindings[0]],
                $this->generateLet(array_merge(
                    [$this->letStarSymbol, \array_slice($bindings, 1)],
                        $body))];
        }

        return array_merge(
            [$this->letSymbol, [$bindings[0]]],
            $body); // unpack the body of let into the lambda exp

    }

    public function evaluate($program, Env\EnvInterface $env, Evaluator $evaluate)
    {
        return $evaluate($this->generateLet($program), $env);
    }
}