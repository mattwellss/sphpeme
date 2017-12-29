<?php

namespace Sphpeme\Env;

class SimpleEnv implements EnvInterface
{
    /**
     * @var array
     */
    private $members;

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    public function __get(string $prop)
    {
        return $this->members[$prop];
    }

    public function __isset(string $prop): bool
    {
        return isset($this->members[$prop]);
    }
}
