<?php

namespace Sphpeme\Env;


trait MappedEnvTrait
{
    public function __isset($prop): bool
    {
        return (bool)$this->__get($prop);
    }

    public function __get(string $prop)
    {
        if (isset(static::MAPPING[$prop])) {
            return $this->__get(static::MAPPING[$prop]);
        }

        return \is_callable([$this, $prop])
            ? [$this, $prop]
            : $this->$prop;
    }
}