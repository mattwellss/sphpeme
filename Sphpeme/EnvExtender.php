<?php

namespace Sphpeme;


use Sphpeme\Env\EnvInterface;

interface EnvExtender
{
    public function __invoke(EnvInterface ...$envs);
}