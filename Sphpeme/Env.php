<?php

namespace Sphpeme;


class Env
{
    protected $extensions = [];

    public function has(string $prop): bool
    {
        return (bool)$this->__get($prop);
    }

    public function extend(Env $extension)
    {
        $new = clone $this;
        $new->extensions[] = $extension;
        return $new;
    }

    public function __get($name)
    {
        foreach ($this->extensions as $extension) {
            if ($extension->has($name)) {
                return $extension->$name;
            }
        }

        return false;
    }
}