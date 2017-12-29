<?php

namespace Sphpeme\Env;

trait MappedEnvTrait
{
    /**
     * @param string $prop
     * @return bool
     */
    public function __isset(string $prop): bool
    {
        return (bool)$this->__get($prop);
    }

    /**
     * @param string $prop
     * @return mixed
     */
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
