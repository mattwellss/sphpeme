<?php

namespace Sphpeme\ExpHandler;


use Sphpeme\Env;
use function Sphpeme\env_extend;
use Sphpeme\EnvExtender;
use Sphpeme\Evaluator;
use Sphpeme\Symbol;

class LambdaExpHandler implements ExpHandler
{
    private $lambdaSymbol;
    /**
     * @var EnvExtender
     */
    private $envExtender;

    public function __construct(EnvExtender $envExtender)
    {
        $this->lambdaSymbol = Symbol::make('lambda');
        $this->envExtender = $envExtender;
    }

    public function evaluate($exp, Env\EnvInterface $env, Evaluator $evaluate)
    {
        list($lambda, $params, $body) = $exp;
        return function (...$args) use ($env, $body, $params, $evaluate) {
            if (\count($params)) {
                $env = ($this->envExtender)($env, new Env\SimpleEnv(array_combine($params, $args)));
            }
            return $evaluate($body, $env);
        };
    }

    public function handles($exp): bool
    {
        return $exp[0] === $this->lambdaSymbol;
    }
}