<?php

namespace Sphpeme\EnvExtender;


use Sphpeme\Env\AggregateEnv;
use Sphpeme\Env\EnvInterface;
use Sphpeme\EnvExtender;

class AggregateEnvExtender implements EnvExtender
{
    public function __invoke(EnvInterface ...$envs)
    {
        return new AggregateEnv(...$envs);
    }
}