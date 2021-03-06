<?php

namespace Sphpeme;


use Sphpeme\ExpHandler\ExpHandler;
use Sphpeme\ExpHandler\ScalarHandler;
use Sphpeme\ExpHandler\SymbolHandler;

class Evaluator
{
    /**
     * @var ExpHandler[]
     */
    private $handlers;

    public function __construct(ExpHandler ...$handlers)
    {
        if (\count($handlers) === 0) {
            $handlers = [
                new SymbolHandler(),
                new ScalarHandler(),
            ];
        }
        $this->handlers = $handlers;
    }

    public function __invoke($exp, Env\EnvInterface $env)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->handles($exp)) {
                return $handler->evaluate($exp, $env, $this);
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