<?php

namespace Sphpeme\Env;


interface EnvInterface
{
    public function __get(string $prop);
    public function __isset(string $prop): bool;
}
