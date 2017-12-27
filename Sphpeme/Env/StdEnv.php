<?php

namespace Sphpeme\Env;


class StdEnv implements EnvInterface
{
    use MappedEnvTrait;

    const MAPPING = [
        'list' => '_list'
    ];


    public function _list(...$args)
    {
        return $args;
    }

    public function car(array $list)
    {
        return current($list);
    }

    public function cdr(array $list)
    {
        return \array_slice($list, 1);
    }

    public function display($arg)
    {
        if (is_scalar($arg) || method_exists($arg, '__toString')) {
            echo $arg;
        } else {
            var_export($arg);
        }
    }

    public function newline()
    {
        echo PHP_EOL;
    }
}
