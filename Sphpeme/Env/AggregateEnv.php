<?php

namespace Sphpeme\Env;



class AggregateEnv implements EnvInterface
{
    protected $extensions = [];

    public function __construct(EnvInterface ...$envs)
    {
        while ($env = array_pop($envs)) {
            $this->extensions[] = $env;
        }
    }

    public function __isset($prop): bool
    {
        foreach ($this->extensions as $extension) {
            if (isset($extension->$prop)) {
                return true;
            }
        }
        return false;
    }

    public function extend(EnvInterface $extension)
    {
        $new = clone $this;
        array_unshift($new->extensions, $extension);
        return $new;
    }

    public function __get(string $prop)
    {
        foreach ($this->extensions as $extension) {
            if (isset($extension->$prop)) {
                return $extension->__get($prop);
            }
        }

        return false;
    }
}