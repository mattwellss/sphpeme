<?php

namespace Sphpeme\Env;


interface EnvInterface
{
    public function __get(string $pop);
    public function __isset($prop): bool;
}